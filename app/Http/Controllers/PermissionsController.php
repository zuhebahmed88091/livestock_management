<?php

namespace App\Http\Controllers;

use App\Exports\PermissionExport;
use App\Helpers\CommonHelper;
use App\Models\Module;
use App\Models\Permission;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable($request);
        }

        $modules = Module::where('status', 'Active')
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id');
        return view('permissions.index', compact('modules'));
    }

    /**
     * Display a json listing for table body.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function getDataTable($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($permission) {
                return view('permissions.action', compact('permission'));
            })
            ->editColumn('module_id', function ($permission) {
                return optional($permission->module)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getQuery($request)
    {
        $query = Permission::query()->with('module');
        if (!empty($request->moduleId)) {
            $query->where('module_id', $request->moduleId);
        }

        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        return $query;
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new PermissionExport($this->getQuery($request)),
            'permission-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $permission = Permission::with('module')->findOrFail($id);
        $view = view('permissions.print_details', compact('permission'));
        CommonHelper::generatePdf($view->render(), 'permission-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return View
     */
    public function create()
    {
        $modules = Module::pluck('name','id')->all();
        return view('permissions.create', compact('modules'));
    }

    /**
     * Store a new permission in the storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            Permission::create($data);

            return redirect()->route('permissions.index')
                             ->with('success_message', __('message.permission_was_successfully_added'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified permission.
     *
     * @param int $id
     *
     * @return View
     */
    public function show($id)
    {
        $permission = Permission::with('module')->findOrFail($id);
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param int $id
     *
     * @return View
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $modules = Module::pluck('name','id')->all();

        return view('permissions.edit', compact('permission','modules'));
    }

    /**
     * Update the specified permission in the storage.
     *
     * @param  int $id
     * @param Request $request
     *
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request, $id);
        try {

            $permission = Permission::findOrFail($id);
            $permission->update($data);

            return redirect()->route('permissions.index')
                             ->with('success_message', __('message.permission_was_successfully_updated'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified permission from the storage.
     *
     * @param  int $id
     *
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return redirect()->route('permissions.index')
                             ->with('success_message', __('message.permission_was_successfully_deleted'));

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
            'module_id' => 'required',
            'name' => 'required|string|min:1|max:191|unique:permissions,name,' . $id,
            'display_name' => 'nullable|string|min:0|max:191',
            'description' => 'nullable|string|min:0|max:191',

        ]);
        $data['name']          = clean($request->name);
        $data['display_name']  = clean($request->display_name);
        $data['description']   = clean($request->description);
        return $data;
    }

}
