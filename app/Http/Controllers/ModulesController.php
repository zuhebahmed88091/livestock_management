<?php

namespace App\Http\Controllers;

use App\Exports\ModuleExport;
use App\Helpers\CommonHelper;
use App\Models\Module;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class ModulesController extends Controller
{
    /**
     * Display a listing of the modules.
     *
     * @return View
     */
    public function index()
    {
        $modules = Module::get();
        return view('modules.index', compact('modules'));
    }

    public function getQuery()
    {
        return Module::query();
    }

    public function exportXLSX()
    {
        return Excel::download(
            new ModuleExport($this->getQuery()),
            'module-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $module = Module::findOrFail($id);
        $view = view('modules.print_details', compact('module'));
        CommonHelper::generatePdf($view->render(), 'module-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new module.
     *
     * @return View
     */
    public function create()
    {
        return view('modules.create');
    }

    /**
     * Store a new module in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            $moduleName = strtolower($request->input('name'));
            $moduleName = Str::singular($moduleName);
            $moduleNamePlural = Str::plural($moduleName);

            $module = Module::create($data);

            $module->permissions()->saveMany([
                new Permission([
                    'name' => $moduleNamePlural . '.index',
                    'display_name' => 'Listing page of all ' . $moduleNamePlural
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.create',
                    'display_name' => 'Display the form for creating new ' . $moduleName
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.edit',
                    'display_name' => 'Display the form for editing a ' . $moduleName
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.show',
                    'display_name' => 'Show detail information for a ' . $moduleName
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.store',
                    'display_name' => 'Store action for creating a new ' . $moduleName
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.update',
                    'display_name' => 'Update action for updating a ' . $moduleName
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.destroy',
                    'display_name' => 'Delete action for removing a ' . $moduleName
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.exportXLSX',
                    'display_name' => 'Export all ' . $moduleNamePlural . ' in excel'
                ]),
                new Permission([
                    'name' => $moduleNamePlural . '.printDetails',
                    'display_name' => 'Export ' . $moduleName . ' details in pdf'
                ])
            ]);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'modules.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('modules.index')
                ->with('success_message', __('message.module_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' =>  $exception->getMessage()]);
        }
    }

    /**
     * Display the specified module.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $module = Module::findOrFail($id);
        return view('modules.show', compact('module'));
    }

    /**
     * Show the form for editing the specified module.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $module = Module::findOrFail($id);
        return view('modules.edit', compact('module'));
    }

    /**
     * Update the specified module in the storage.
     *
     * @param int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request, $id);
        try {
           
            $module = Module::findOrFail($id);
            $oldData = $module->toArray();
            $module->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'modules.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('modules.index')
                ->with('success_message', __('message.module_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified module from the storage.
     *
     * @param int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $module = Module::findOrFail($id);
            $oldData = $module->toArray();
            $module->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'modules.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()
                ->route('modules.index')
                ->with('success_message', __('message.module_was_successfully_deleted'));
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
            'name' => 'required|string|min:1|max:100|unique:modules,name,' . $id,
            'slug' => 'required|string|min:1|max:100|unique:modules,slug,' . $id,
            'fa_icon' => 'required|string|min:1|max:20',
            'status' => 'required',
            'sorting' => 'required'
        ]);
        $data['name']     = clean($request->name);
        $data['slug']     = clean($request->slug);
        $data['fa_icon']  = clean($request->fa_icon);
        $data['sorting']  = clean($request->sorting);
        return $data;
    }
}
