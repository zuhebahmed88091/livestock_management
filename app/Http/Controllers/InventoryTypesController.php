<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\InventoryType;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;

class InventoryTypesController extends Controller
{
    /**
     * Display a listing of the inventory types.
     *
     * @return View
     */
    public function index()
    {
        $inventoryTypes = InventoryType::get();
        return view('inventory_types.index', compact('inventoryTypes'));
    }

    /**
     * Show the form for creating a new inventory type.
     *
     * @return View
     */
    public function create()
    {
        return view('inventory_types.create');
    }

    /**
     * Store a new inventory type in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            InventoryType::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_types.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('inventory_types.index')
                             ->with('success_message', __('message.inventory_type_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified inventory type.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $inventoryType = InventoryType::findOrFail($id);
        return view('inventory_types.show', compact('inventoryType'));
    }

    /**
     * Show the form for editing the specified inventory type.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $inventoryType = InventoryType::findOrFail($id);
        return view('inventory_types.edit', compact('inventoryType'));
    }

    /**
     * Update the specified inventory type in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $inventoryType = InventoryType::findOrFail($id);
            $oldData = $inventoryType->toArray();
            $inventoryType->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_types.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('inventory_types.index')
                             ->with('success_message', __('message.inventory_type_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified inventory type from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $inventoryType = InventoryType::findOrFail($id);
            $oldData = $inventoryType->toArray();
            $inventoryType->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'inventory_types.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('inventory_types.index')
                             ->with('success_message',  __('message.inventory_type_was_successfully_deleted'));
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
            'title' => 'required|string|min:1|max:255',
            'status' => 'required',
            'inventory_group' => 'required',

        ]);
        $data['title']  = clean($request->title);
        return $data;
    }
}
