<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Helpers\CommonHelper;
use App\Models\Country;
use App\Models\FileType;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return View
     */
    public function index()
    {
        $users = User::with('country')->get();
        return view('users.index', compact('users'));
    }

    public function getQuery()
    {
        return User::query()->with('country');
    }

    public function exportXLSX()
    {
        return Excel::download(
            new UserExport($this->getQuery()),
            'user-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $user = User::with('country')->findOrFail($id);
        $view = view('users.print_details', compact('user'));
        CommonHelper::generatePdf($view->render(), 'user-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return View
     */
    public function create()
    {
        $roles = Role::all()->pluck('name', 'id');
        $countries = Country::all()->pluck('country_name','id');
        return view('users.create', compact('roles', 'countries'));
    }

    /**
     * Store a new user in the storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            
            $user = User::create($data);

            if (!empty($request->input('assign_roles'))) {
                $user->roles()->sync($request->input('assign_roles'));
            }

            if ($request->hasFile('photo')) {
                // upload photo
                $photo = $request->file('photo');
                $fileExtension = $photo->getClientOriginalExtension();
                $filename = $user->id . '.' . $fileExtension;
                $photo->storeAs('profiles', $filename);

                // get file type from databse
                $fileType = FileType::where('name', $fileExtension)->first();
                if (empty($fileType)) {
                    $fileTypeId = 1;
                } else {
                    $fileTypeId = $fileType->id;
                }

                // create a new entry for uploaded file in db
                $user->uploadedFile()->create([
                    'filename' => $filename,
                    'file_type_id' => $fileTypeId,
                    'original_filename' => $photo->getClientOriginalName()
                ]);
            }

            return redirect()->route('users.index')
                             ->with('success_message', __('message.user_was_successfully_added'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     *
     * @return View
     */
    public function show($id)
    {
        $user = User::with('country')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param int $id
     *
     * @return View
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all()->pluck('name', 'id');
        $countries = Country::all()->pluck('country_name','id');
        return view('users.edit', compact('user', 'roles', 'countries'));
    }

    /**
     * Update the specified user in the storage.
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
            
            // update existing users
            $user = User::findOrFail($id);
            $user->update($data);

            // assign roles
            if (!empty($request->input('assign_roles'))) {
                $user->roles()->sync($request->input('assign_roles'));
            }

            // upload photo if submit
            if ($request->hasFile('photo')) {
                $previousFileName = optional($user->uploadedFile)->filename;

                // upload photo
                $photo = $request->file('photo');
                $fileExtension = $photo->getClientOriginalExtension();
                $filename = $id . '.' . $fileExtension;
                $photo->storeAs('profiles', $filename);

                // get file type from databse
                $fileType = FileType::where('name', $fileExtension)->first();
                if (empty($fileType)) {
                    $fileTypeId = 1;
                } else {
                    $fileTypeId = $fileType->id;
                }

                // create a new entry for uploaded file in db
                if ($previousFileName) {
                    $user->uploadedFile()->update([
                        'filename' => $filename,
                        'file_type_id' => $fileTypeId,
                        'original_filename' => $photo->getClientOriginalName()
                    ]);
                } else {
                    $user->uploadedFile()->create([
                        'filename' => $filename,
                        'file_type_id' => $fileTypeId,
                        'original_filename' => $photo->getClientOriginalName()
                    ]);
                }

                // remove old file when different extension
                if ($previousFileName && $previousFileName != $filename) {
                    Storage::delete('profiles/' . $previousFileName);
                }
            }

            return redirect()->route('users.index')
                             ->with('success_message', __('message.user_was_successfully_updated'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified user from the storage.
     *
     * @param  int $id
     *
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('users.index')
                             ->with('success_message', __('message.user_was_successfully_deleted'));

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
        $rules = [
            'name' => 'required|string|min:1|max:191',
            'email' => 'required|email|min:1|max:191|unique:users,email,' . $id,
            'country_id' => 'required|numeric',
            'phone' => 'required|string|min:1|max:20',
            'assign_roles'  => 'required|array|min:1',
            'status' => 'required'
        ];

        if ($request->isMethod('POST')) {
            $rules['photo'] = 'required|file|max:2048';
            $rules['password'] = 'required|string|min:6|confirmed';
        } else {
            if (!empty($request->input('password'))) {
                $rules['password'] = 'required|string|min:6|confirmed';
            }

            if ($request->hasFile('photo')) {
                $rules['photo'] = 'required|file|max:2048';
            }
        }

        $data = $request->validate($rules);

        if (!empty($data['photo'])) {
            unset($data['photo']);
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (!empty($data['assign_roles'])) {
            unset($data['assign_roles']);
        }

        $data['name']    = clean($request->name);
        $data['email']   = clean($request->email);
        $data['phone']   = clean($request->phone);

        return $data;
    }
}
