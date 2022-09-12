<?php

namespace App\Http\Controllers;

use App\Models\FileType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileTypesController extends Controller
{

    /**
     * Display a listing of the file types.
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        $fileTypes = FileType::get();

        return view('file_types.index', compact('fileTypes'));
    }

    /**
     * Show the form for creating a new file type.
     *
     * @return Illuminate\View\View
     */
    public function create()
    {


        return view('file_types.create');
    }

    /**
     * Store a new file type in the storage.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            FileType::create($data);

            return redirect()->route('file_types.index')
                             ->with('success_message', __('message.file_type_was_successfully_added'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified file type.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function show($id)
    {
        $fileType = FileType::findOrFail($id);

        return view('file_types.show', compact('fileType'));
    }

    /**
     * Show the form for editing the specified file type.
     *
     * @param int $id
     *
     * @return Illuminate\View\View
     */
    public function edit($id)
    {
        $fileType = FileType::findOrFail($id);


        return view('file_types.edit', compact('fileType'));
    }

    /**
     * Update the specified file type in the storage.
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

            $fileType = FileType::findOrFail($id);
            $fileType->update($data);

            return redirect()->route('file_types.index')
                             ->with('success_message', __('message.file_type_was_successfully_updated'));

        } catch (Exception $exception) {

            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified file type from the storage.
     *
     * @param  int $id
     *
     * @return Illuminate\Http\RedirectResponse | Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try {
            $fileType = FileType::findOrFail($id);
            $fileType->delete();

            return redirect()->route('file_types.index')
                             ->with('success_message', __('message.file_type_was_successfully_deleted'));

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
            'name' => 'required|string|min:1|max:10',
            'status' => 'required',

        ]);


        $data['name'] = clean($request->name);

        return $data;
    }

}
