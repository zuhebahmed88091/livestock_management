<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InventoryUnit;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class InventoryUnitsController extends Controller
{
    /**
     * Display a listing of the inventory units.
     *
     * @return View
     */
    public function index()
    {
        $inventoryUnits = InventoryUnit::get();
        return view('inventory_units.index', compact('inventoryUnits'));
    }

    /**
     * Show the form for creating a new inventory unit.
     *
     * @return View
     */
    public function create()
    {
        return view('inventory_units.create');
    }

    /**
     * Store a new inventory unit in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            InventoryUnit::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_units.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('inventory_units.index')
                             ->with('success_message', __('message.inventory_unit_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified inventory unit.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $inventoryUnit = InventoryUnit::findOrFail($id);
        return view('inventory_units.show', compact('inventoryUnit'));
    }

    /**
     * Show the form for editing the specified inventory unit.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $inventoryUnit = InventoryUnit::findOrFail($id);
        return view('inventory_units.edit', compact('inventoryUnit'));
    }

    /**
     * Update the specified inventory unit in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request, $id);
        try {

            $inventoryUnit = InventoryUnit::findOrFail($id);
            $oldData = $inventoryUnit->toArray();
            $inventoryUnit->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_units.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('inventory_units.index')
                             ->with('success_message', __('message.inventory_unit_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified inventory unit from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $inventoryUnit = InventoryUnit::findOrFail($id);
            $oldData = $inventoryUnit->toArray();
            $inventoryUnit->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_units.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('inventory_units.index')
                             ->with('success_message', __('message.inventory_unit_was_successfully_deleted'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Request $request
     * @param int $id
     * @return array
     */
    protected function getData(Request $request, $id = 0)
    {
        $data = $request->validate([
            'title' => 'required|string|min:1|max:255|unique:inventory_units,title,' . $id,
            'status' => 'required',
        ]);
        $data['title']  = clean($request->title);
        return $data;
    }
}
