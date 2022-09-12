<?php

namespace App\Http\Controllers;

use App\Exports\RoleExport;
use App\Helpers\CommonHelper;
use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class RolesController extends Controller
{
    /**
     * Display a listing of the roles.
     *
     * @return View
     */
    public function index()
    {
        $roles = Role::get();
        return view('roles.index', compact('roles'));
    }

    public function getQuery()
    {
        return Role::query();
    }

    public function exportXLSX()
    {
        return Excel::download(
            new RoleExport($this->getQuery()),
            'role-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $role = Role::findOrFail($id);
        $view = view('roles.print_details', compact('role'));
        CommonHelper::generatePdf($view->render(), 'role-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new role.
     *
     * @return View
     */
    public function create()
    {
        $myValue = "This is Zuheb";
        return view('roles.create',compact('myValue'));
    }

    /**
     * Store a new role in the storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            Role::create($data);

            return redirect()->route('roles.index')
                ->with('success_message',  __('message.role_was_successfully_added'));

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified role.
     *
     * @param int $id
     *
     * @return View
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified role.
     *
     * @param int $id
     *
     * @return View
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified role in the storage.
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

            $role = Role::findOrFail($id);
            $role->update($data);

            return redirect()->route('roles.index')
                ->with('success_message',  __('message.role_was_successfully_updated'));

        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    public function modulePermissions($roleId, $moduleId = 0)
    {
        $totalChecked = 0;
        $role = Role::findOrFail($roleId);
        $modules = Module::where('status', 'Active')->orderBy('sorting', 'ASC')->get();
        if (empty($moduleId)) {
            $selectedModule = $modules->first();
        } else {
            $selectedModule = Module::with('permissions')->findOrFail($moduleId);
        }

        $totalOptions = count($selectedModule->permissions);
        foreach ($selectedModule->permissions as $permission) {
            if ($permission->roles->where('id', $role->id)->first()) {
                $totalChecked++;
                $permission->isChecked = true;
            } else {
                $permission->isChecked = false;
            }
        }

        if ($totalOptions == $totalChecked) {
            $selectedModule->iconCheckAll = 'fa-check-square';
            $selectedModule->isChecked = true;
        } else if($totalChecked > 0) {
            $selectedModule->iconCheckAll = 'fa-minus-square';
            $selectedModule->isChecked = false;
        } else {
            $selectedModule->isChecked = false;
            $selectedModule->iconCheckAll = 'fa-square';
        }

        return view('roles.assign_module_permissions', compact('modules', 'selectedModule', 'role'));
    }

    public function assignPermissions(Request $request)
    {
        $roleId = $request->input('roleId');
        $moduleId = $request->input('moduleId');
        $permissionIds = $request->input('permissionIds');

        try {
            $role = Role::findOrFail($roleId);

            $syncData = [];
            if (!empty($permissionIds)) {
                foreach ($permissionIds as $permissionId) {
                    $syncData[$permissionId] = ['module_id' => $moduleId];
                }
            }
            $role->savePermissionsWithModule($syncData, $moduleId);

            return ['status' => 'OK', 'message' => __('message.update_successful')];
        } catch (Exception $exception) {
            return ['status' => 'FAILED', 'message' => __('message.something_goes_wrong')];
        }
    }

    /**
     * Remove the specified role from the storage.
     * @param  int $id
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return redirect()->route('roles.index')
                ->with('success_message', __('message.role_was_successfully_deleted'));

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
            'name' => 'required|string|min:1|max:191|unique:roles,name,' . $id,
            'display_name' => 'nullable|string|min:0|max:191',
            'description' => 'nullable|string|min:0|max:191',
        ]);

        $data['name']          = clean($request->name);
        $data['display_name']  = clean($request->display_name);
        $data['description']   = clean($request->description);

        return $data;
    }
}
