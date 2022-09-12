<?php

namespace App\Http\Controllers;

use Exception;
use Dompdf\Dompdf;

use App\Models\EventLog;
use App\Models\Inventory;
use Illuminate\View\View;
use App\Helpers\EventHelper;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Models\InventoryType;
use App\Models\InventoryUnit;
use Illuminate\Http\Response;
use App\Exports\InventoryExport;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\Rules\In;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class InventoriesController extends Controller
{
    /**
     * Display a listing of the inventories.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->inventoryList($request);
        }

        $inventoryTypes = InventoryType::where('status', 'Active')->orderBy('title', 'ASC')
            ->pluck('title', 'id');
        
        $inventoryUnits = InventoryUnit::where('status', 'Active')->orderBy('title', 'ASC')
            ->pluck('title', 'id');

        return view('inventories.index', compact('inventoryTypes', 'inventoryUnits'));
    }

    public function getQuery($request)
    {
        $query = Inventory::query()->with('creator');

        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if (!empty($request->inventoryTypeId)) {
            $query->where('inventory_type_id', $request->inventoryTypeId);
        }

        if (!empty($request->inventoryUnitId)) {
            $query->where('inventory_unit_id', $request->inventoryUnitId);
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
    public function inventoryList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($inventory) {
                return view('inventories.action', compact('inventory'));
            })
            ->editColumn('image', function ($inventory) {
                $image = '';
                if (!empty($inventory->inventory_image)) {
                    $imageUrl = asset('storage/' . $inventory->inventory_image);
                    $imageTitle = $inventory->name;
                    $image = view('commons.image_in_list', compact('imageUrl', 'imageTitle'));
                }
                return $image;
            })
            ->editColumn('inventory_type', function ($inventory) {
                return optional($inventory->inventoryType)->title;
            })
            ->editColumn('inventory_unit', function ($inventory) {
                return optional($inventory->inventoryUnit)->title;
            })
            ->rawColumns(['action', 'image'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new InventoryExport($this->getQuery($request)),
            'inventory-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $inventory = Inventory::with('creator')->findOrFail($id);
        $view = view('inventories.print_details', compact('inventory'));
        CommonHelper::generatePdf($view->render(), 'Inventory-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new inventory.
     *
     * @return View
     */
    public function create()
    {
        $inventoryTypes = InventoryType::all()->pluck('title','id');
		$inventoryUnits = InventoryUnit::all()->pluck('title','id');
        return view('inventories.create', compact('inventoryTypes','inventoryUnits'));
    }

    /**
     * Store a new inventory in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Inventory::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventories.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('inventories.index')
                             ->with('success_message', __('message.inventory_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified inventory.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $inventory = Inventory::with('inventorytype','inventoryunit','creator')->findOrFail($id);
        return view('inventories.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified inventory.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);
        $inventoryTypes = InventoryType::all()->pluck('title','id');
		$inventoryUnits = InventoryUnit::all()->pluck('title','id');
        return view('inventories.edit', compact('inventory','inventoryTypes','inventoryUnits'));
    }

    /**
     * Update the specified inventory in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $inventory = Inventory::findOrFail($id);
            $oldData = $inventory->toArray();
            if(isset($data['inventory_image'])){
                Storage::delete($inventory->inventory_image);
            }
            $inventory->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventories.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('inventories.index')
                             ->with('success_message', __('message.inventory_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified inventory from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $inventory = Inventory::findOrFail($id);
            $oldData = $inventory->toArray();
            Storage::delete($inventory->inventory_image);
            $inventory->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventories.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('inventories.index')
                             ->with('success_message', __('message.inventory_was_successfully_deleted'));
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
            'name' => 'required|string|min:1|max:255',
            'inventory_image' => ['file'],
            'inventory_type_id' => 'required',
            'inventory_unit_id' => 'required',
            'source' => 'required|string|min:1|max:100',
            'warranty' => 'required|string|min:1|max:100',
            'description' => 'required',
            'instruction' => 'required',

        ]);

        if ($request->has('custom_delete_inventory_image')) {
            $data['inventory_image'] = '';
        }
        if ($request->hasFile('inventory_image')) {
            $data['inventory_image'] = $this->moveFile($request->file('inventory_image'));
        }

        $data['name']        = clean($request->name);
        $data['source']      = clean($request->source);
        $data['warranty']    = clean($request->warranty);
        $data['description'] = clean($request->description);
        $data['instruction'] = clean($request->instruction);


        return $data;
    }

    /**
     * Moves the attached file to the server.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return string
     */
    protected function moveFile($file)
    {
        if (!$file->isValid()) {
            return '';
        }

        return $file->store('uploads');
    }
}
