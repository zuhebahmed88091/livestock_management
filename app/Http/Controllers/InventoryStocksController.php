<?php

namespace App\Http\Controllers;

use App\Exports\InventoryStockExport;
use App\Helpers\CommonHelper;
use App\Models\Inventory;
use Exception;
use Illuminate\Http\Request;
use App\Models\InventoryStock;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class InventoryStocksController extends Controller
{
    /**
     * Display a listing of the inventory stocks.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->stockSummery($request);
        }

        $inventories = Inventory::orderBy('name', 'ASC')->pluck('name', 'id');
        return view('inventory_stocks.index', compact('inventories'));
    }

    public function getQuery($request)
    {
        $query = Inventory::query()->with('inventoryType', 'inventoryUnit', 'creator');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('created_at', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->inventoryId)) {
            $query->where('id', $request->inventoryId);
        }

        return $query;
    }

    /**
     * Display a json listing for table body.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function stockSummery($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function($inventory){
                return view('inventory_stocks.action', compact('inventory'));
            })
            ->addColumn('totalStockCost', function($inventory){
                return InventoryStock::where('inventory_id', $inventory->id)
                    ->where('quantity', '>', 0)
                    ->sum('cost');
            })
            ->addColumn('addedBy', function($inventory){
                return optional($inventory->creator)->name;
            })
            ->addColumn('totalStockQuantity', function($inventory){
                $unit = optional($inventory->inventoryUnit)->title;
                return InventoryStock::where('inventory_id', $inventory->id)
                    ->where('quantity', '>', 0)
                    ->sum('quantity') . ' ' . $unit;
            })
            ->addColumn('totalConsumeQuantity', function($inventory){
                $unit = optional($inventory->inventoryUnit)->title;
                $totalConsumeQuantity = InventoryStock::where('inventory_id', $inventory->id)
                        ->where('quantity', '<', 0)
                        ->sum('quantity');
                return ($totalConsumeQuantity * -1) . ' ' . $unit;
            })
            ->addColumn('currentStockQuantity', function($inventory){
                $unit = optional($inventory->inventoryUnit)->title;
                $totalStockQuantity = InventoryStock::where('inventory_id', $inventory->id)
                    ->where('quantity', '>', 0)
                    ->sum('quantity');
                $totalConsumeQuantity = InventoryStock::where('inventory_id', $inventory->id)
                    ->where('quantity', '<', 0)
                    ->sum('quantity');
                $currentStockQuantity = $totalStockQuantity + $totalConsumeQuantity;
                return $currentStockQuantity . ' ' . $unit;
            })
            ->editColumn('inventory_image', function($inventory) {
                $imageUrl = asset('storage/' . $inventory->inventory_image);
                $imageTitle = $inventory->name;
                return view('commons.image_in_list', compact('imageUrl', 'imageTitle'));
            })
            ->rawColumns(['action', 'inventory_image'])
            ->make(true);
    }

    function setStockSummery($inventory)
    {
        $queryAdd = InventoryStock::where('inventory_id', $inventory->id)->where('quantity', '>', 0);
        $queryConsume = InventoryStock::where('inventory_id', $inventory->id)->where('quantity', '<', 0);

        $unit = $inventory->inventoryUnit->title;
        $totalStockQuantity = $queryAdd->sum('quantity');
        $totalConsumeQuantity = $queryConsume->sum('quantity');
        $currentStockQuantity = $totalStockQuantity + $totalConsumeQuantity;

        $inventory->totalStockCost = $queryAdd->sum('cost');
        $inventory->addedBy = optional($inventory->creator)->name;
        $inventory->totalStockQuantity = $totalStockQuantity . ' ' . $unit;
        $inventory->totalConsumeQuantity = ($totalConsumeQuantity * -1) . ' ' . $unit;
        $inventory->currentStockQuantity = $currentStockQuantity . ' ' . $unit;
        $inventory->unit = $unit;

        return $inventory;
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new InventoryStockExport($this->getQuery($request)),
            'inventory-stock-' . time() . '.csv'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $inventory = Inventory::with('inventorytype','inventoryunit','creator')->findOrFail($id);
        $selectedInventory = $this->setStockSummery($inventory);

        $inventoryStocks = InventoryStock::where('inventory_id', $id)->get();
        $view = view('inventory_stocks.print_details', compact('inventoryStocks', 'selectedInventory'))
            ->with('cumulativeSum', 0);
        CommonHelper::generatePdf($view->render(), 'Inventory-stock-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new inventory stock.
     *
     * @param int $inventoryId
     * @return View
     */
    public function create($inventoryId)
    {
        $inventory = Inventory::with('inventorytype','inventoryunit','creator')->findOrFail($inventoryId);
        $selectedInventory = $this->setStockSummery($inventory);

        $inventories = Inventory::all()->pluck('name','id');
        return view('inventory_stocks.create', compact('inventories', 'selectedInventory'));
    }

    /**
     * Show the form for creating a new inventory stock.
     *
     * @param int $inventoryId
     * @return View
     */
    public function consume($inventoryId)
    {
        $inventory = Inventory::with('inventorytype','inventoryunit','creator')->findOrFail($inventoryId);
        $selectedInventory = $this->setStockSummery($inventory);

        $inventories = Inventory::all()->pluck('name','id');
        return view('inventory_stocks.consume', compact('inventories', 'selectedInventory'));
    }

    /**
     * Show the form for creating a new inventory stock.
     *
     * @param int $inventoryId
     * @return View
     */
    public function details($inventoryId)
    {
        $inventory = Inventory::with('inventorytype','inventoryunit','creator')->findOrFail($inventoryId);
        $selectedInventory = $this->setStockSummery($inventory);

        $inventoryStocks = InventoryStock::where('inventory_id', $inventoryId)->get();
        return view('inventory_stocks.details', compact('inventoryStocks', 'selectedInventory'))
            ->with('cumulativeSum', 0);
    }

    /**
     * Store a new inventory stock in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            $data['created_by'] = Auth::user()->id;
            InventoryStock::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_stocks.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('inventory_stocks.index')
                             ->with('success_message', __('message.inventory_stock_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified inventory stock.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $inventoryStock = InventoryStock::with('inventory','creator')->findOrFail($id);
        return view('inventory_stocks.show', compact('inventoryStock'));
    }

    /**
     * Show the form for editing the specified inventory stock.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $inventoryStock = InventoryStock::findOrFail($id);
        $inventories = Inventory::all()->pluck('name','id')->except($id);
        return view('inventory_stocks.edit', compact('inventoryStock','inventories'));
    }

    /**
     * Update the specified inventory stock in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $inventoryStock = InventoryStock::findOrFail($id);
            $oldData = $inventoryStock->toArray();
            $inventoryStock->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_stocks.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('inventory_stocks.index')
                             ->with('success_message', __('message.inventory_stock_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified inventory stock from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $inventoryStock = InventoryStock::findOrFail($id);
            $oldData = $inventoryStock->toArray();
            $inventoryStock->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_stocks.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('inventory_stocks.index')
                             ->with('success_message', __('message.inventory_stock_was_successfully_deleted'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }

    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $costRule = 'required|numeric|min:0|max:2147483647';
        if (!empty($request->stock_type) && $request->stock_type == 1) {
            $costRule = 'nullable';
        }
        $data = $request->validate([
            'inventory_id' => 'required',
            'quantity' => 'required|numeric|min:0|max:2147483647',
            'cost' => $costRule,
        ]);

        if (!empty($request->stock_type) && $request->stock_type == 1) {
            $data['quantity'] = $data['quantity'] * -1;
        }

        return $data;
    }

}
