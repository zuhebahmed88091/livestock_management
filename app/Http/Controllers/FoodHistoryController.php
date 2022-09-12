<?php

namespace App\Http\Controllers;
use App\Exports\FoodHistoryExport;
use App\Helpers\CommonHelper;
use App\Models\FoodHistory;
use App\Models\Shed;
use App\Models\LiveStock;
use App\Models\LiveStockType;
use App\Models\InventoryType;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class FoodHistoryController extends Controller
{
    /**
     * Display a listing of the livestocks.Query??
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->foodHistory($request);
        }

        $foodHistory = FoodHistory::get();
        $liveStocks = LiveStock::get();
        $inventoryTypes = InventoryType::where('inventory_group', 'Food')->get();
        $liveStockTypes = LiveStockType::get();
        $sheds = Shed::all();

        return view('food_history.index', compact('foodHistory','inventoryTypes','liveStockTypes','sheds','liveStocks'));
    }
    public function getQuery($request)
    {
        $query = FoodHistory::query()->with('shed','inventory','liveStock');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('date', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->liveStockTypeId)) {
            $query->where('livestock_type_id', $request->liveStockTypeId);
        }
        
        if (!empty($request->shedId)) {
            $query->where('shed_id', $request->shedId);
        }
        if (!empty($request->inventoryFoodId)) {
            $query->where('inventory_id', $request->inventoryFoodId);
        }
        if (!empty($request->livestockId)) {
            $query->where('livestock_id', $request->livestockId);
        }

        return $query;
    }



    public function foodHistory($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($foodHistory) {
                return view('food_history.action', compact('foodHistory'));
            })
            ->addColumn('livestock_type_id', function ($foodHistory) {
                return optional(optional($foodHistory->liveStock)->livestockType)->title;
            })
             ->editColumn('livestock_id', function ($foodHistory) {

                return optional($foodHistory->liveStock)->batch_name;
             })
              ->editColumn('shed_id', function ($foodHistory) {

                return optional($foodHistory->shed)->shed_no;
             })
             ->editColumn('inventory_id', function ($foodHistory) {

                return optional($foodHistory->inventory)->title;
             })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new FoodHistoryExport($this->getQuery($request)),
            'foodHistory-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $foodHistory = FoodHistory::with('shed','inventory','liveStock')->findOrFail($id);
        $view = view('food_history.print_details', compact('foodHistory'));
        CommonHelper::generatePdf($view->render(), 'Food-History-details-' . date('Ymd'));
    }
    /**
     * Show the form for creating a new livestock type.
     *
     * @return View
     */

    public function create()
    {
        $foodHistory = FoodHistory::all();
        $liveStocks = LiveStock::all();
        $liveStockTypes = LiveStockType::where('status', 'Active')->get();
        $sheds = Shed::all();
        $inventoryTypes = InventoryType::where('inventory_group', 'Food')->get();
        return view('food_history.create', compact('foodHistory', 'sheds','inventoryTypes','liveStockTypes','liveStocks'));
    }

    /**
     * Store a new livestock type in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            FoodHistory::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'food_history.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('foodHistory.index')
                             ->with('success_message',  __('message.foodHistory_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified livestock type.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $foodHistory = FoodHistory::with('shed','inventory','liveStock')->findOrFail($id);

        return view('food_history.show', compact('foodHistory'));
    }

    /**
     * Show the form for editing the specified livestock type.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $foodHistory = FoodHistory::with('shed','inventory','liveStock')->findOrFail($id);
        $liveStocks = LiveStock::all();
        $liveStockTypes = LiveStockType::where('status', 'Active')->get();
        $sheds = Shed::all();
        $inventoryTypes = InventoryType::where('inventory_group', 'Food')->get();
        return view('food_history.edit', compact('sheds','foodHistory','inventoryTypes','liveStockTypes','liveStocks'));
    }
     /**
     * Update the specified livestock type in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {
            $foodHistory = FoodHistory::findOrFail($id);
            $oldData = $foodHistory->toArray();
            $foodHistory->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'foodHistory.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('foodHistory.index')
                             ->with('success_message', __('message.foodHistory_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $foodHistory = FoodHistory::findOrFail($id);
            $oldData = $foodHistory->toArray();
            $foodHistory->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'foodHistory.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('foodHistory.index')
                             ->with('success_message', __('message.foodHistory_was_successfully_deleted'));
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
        $data = $request->validate([
            'livestock_id' => 'required',
            'shed_id' => 'required',
            'inventory_id' => 'required',
            'consume_quantity' => 'required',
            'duration' => 'required|string|min:1|max:50',
            'date' => 'required',
            'comments' => 'required'


        ]);

        $data['livestock_type_id'] = clean($request->livestock_type_id);
        $data['livestock_id'] = clean($request->livestock_id);
        $data['shed_id'] = clean($request->shed_id);
        $data['inventory_id'] = clean($request->inventory_id);
        $data['consume_quantity'] = clean($request->consume_quantity);
        $data['duration'] = clean($request->duration);
        $data['date'] = clean($request->date);
        $data['comments'] = clean($request->comments);


        return $data;
    }
}
