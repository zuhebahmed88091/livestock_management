<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FileType;
use App\Models\UploadedFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadedFilesController extends Controller
{

    /**
     * Display a listing of the uploaded files.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $uploadedFiles = UploadedFile::with('filetype','user')->get();

        return view('uploaded_files.index', compact('uploadedFiles'));
    }

    /**
     * Show the form for creating a new uploaded file.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {
        $fileTypes = FileType::pluck('name','id')->all();
$users = User::pluck('name','id')->all();

        return view('uploaded_files.create', compact('fileTypes','users'));
    }

    /**
     * Store a new uploaded file in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);

        try {

            UploadedFile::create($data);

            return redirect()->route('uploaded_files.index')
                             ->with('success_message', __('message.uploaded_file_was_successfully_added'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified uploaded file.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $uploadedFile = UploadedFile::with('filetype','user')->findOrFail($id);

        return view('uploaded_files.show', compact('uploadedFile'));
    }

    /**
     * Show the form for editing the specified uploaded file.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $uploadedFile = UploadedFile::findOrFail($id);
        $fileTypes = FileType::pluck('name','id')->all();
        $users = User::pluck('name','id')->all();

        return view('uploaded_files.edit', compact('uploadedFile','fileTypes','users'));
    }

    /**
     * Update the specified uploaded file in the storage.
     *
     * @param  int $id
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {
            $uploadedFile = UploadedFile::findOrFail($id);
            $uploadedFile->update($data);

            return redirect()->route('uploaded_files.index')
                             ->with('success_message', __('message.uploaded_file_was_successfully_updated'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified uploaded file from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $uploadedFile = UploadedFile::findOrFail($id);
            $uploadedFile->delete();

            return redirect()->route('uploaded_files.index')
                             ->with('success_message', __('message.uploaded_file_was_successfully_deleted'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request!']);
        }
    }


    /**
     * Get the request's data from the request.
     *
     * @param Illuminate\Http\Request\Request $request
     * @return array
     */
    protected function getData(Request $request)
    {
        $data = $request->validate([
            'filename' => 'required|string|min:1|max:50',
            'original_filename' => 'required|string|min:1|max:255',
            'file_type_id' => 'required',
            'user_id' => 'required',

        ]);

        $data['filename']           = clean($request->filename);
        $data['original_filename']  = clean($request->original_filename);


        return $data;
    }

}
