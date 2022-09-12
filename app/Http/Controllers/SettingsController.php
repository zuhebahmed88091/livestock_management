<?php

namespace App\Http\Controllers;

use App\Models\FileType;
use App\Models\Setting;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Auth;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display a listing of the settings.
     *
     * @return View
     */
    public function index()
    {
        $settings = Setting::get();
        return view('settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new setting.
     *
     * @return View
     */
    public function create()
    {
        return view('settings.create');
    }

    /**
     * Store a new setting in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            Setting::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'settings.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('settings.index')
                             ->with('success_message', __('message.setting_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified setting.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings.show', compact('setting'));
    }

    /**
     * Show the form for editing the specified setting.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $setting = Setting::findOrFail($id);
        return view('settings.edit', compact('setting'));
    }

    /**
     * Update the specified setting in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);

        try {

            $setting = Setting::findOrFail($id);
            $oldData = $setting->toArray();
            $setting->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'settings.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('settings.index')
                             ->with('success_message', __('message.setting_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified setting from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $setting = Setting::findOrFail($id);
            $oldData = $setting->toArray();
            $setting->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'settings.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('settings.index')
                             ->with('success_message', __('message.setting_was_successfully_deleted'));
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
            'title' => 'required|string|min:1|max:100',
            'constant' => 'required|string|min:1|max:255',
            'value' => 'required|string|min:1|max:100',
            'field_type' => 'required',
            'options' => 'required|string|min:1|max:255',
            'status' => 'required',

        ]);
        $data['title']     = clean($request->title);
        $data['constant']  = clean($request->constant);
        $data['value']     = clean($request->value);
        $data['options']   = clean($request->options);

        return $data;
    }

    /**
     * Show the form for updating all setting.
     *
     * @return View
     */
    public function all()
    {
        $settings = Setting::orderBy('sorting', 'ASC')->get();
        if (!empty($settings)) {
            foreach ($settings as $setting) {
                $options = array();
                if ($setting->field_type == 'Options') {
                    $optionList = explode(',', $setting->options);
                    foreach ($optionList as $singleOption) {
                        $parts = explode('|', $singleOption);
                        $options[$parts[1]] = $parts[0];
                    }
                    $setting->options = $options;
                } else if ($setting->field_type == 'Select' || $setting->field_type == 'MultiSelect') {
                    $optionList = explode(',', $setting->options);
                    foreach ($optionList as $singleOption) {
                        $parts = explode('|', $singleOption);
                        $options[$parts[1]] = $parts[0];
                    }
                    $setting->options = $options;
                } else if ($setting->field_type == 'DateFormat') {
                    $setting->options = explode('|', $setting->options);
                } else if ($setting->field_type == 'Boolean') {
                    list($setting->dataOn, $setting->dataOff) = explode('|', $setting->options);
                }
            }
        }

        return view('settings.all', compact('settings'));
    }

    /**
     * Update the specified setting in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function updateBatch(Request $request)
    {   
        
        $request->validate([
                'site_name'           => 'required|string|min:1|max:100',
                'site_short_name'     => 'required|string|min:1|max:100',
                'site_email'          => 'required|string|min:1|max:100|email',
                'site_phone'          => 'required|string|min:1|max:100',
                'footer_text'         => 'required|string|min:1|max:100',
                'version'             => 'required|string|min:1|max:100',
        ]);
        
        try {
            $inputList = array_change_key_case($request->except(['_method','_token']), CASE_UPPER);
            
            $inputList['SITE_NAME']       = clean($inputList['SITE_NAME']);
            $inputList['SITE_SHORT_NAME'] = clean($inputList['SITE_SHORT_NAME']);
            $inputList['SITE_EMAIL']      = clean($inputList['SITE_EMAIL']);
            $inputList['SITE_PHONE']      = clean($inputList['SITE_PHONE']);
            $inputList['FOOTER_TEXT']     = clean($inputList['FOOTER_TEXT']);
            $inputList['VERSION']         = clean($inputList['VERSION']);
            
            $settings = Setting::orderBy('sorting', 'ASC')->get();
            if (!empty($settings)) {
                foreach ($settings as $setting) {
                    if (!empty($inputList[$setting->constant])) {
                        if ($setting->field_type == 'File') {
                            if ($request->hasFile($setting->constant)) {
                                // upload photo
                                $photo = $request->file($setting->constant);
                                $fileExtension = $photo->getClientOriginalExtension();

                                // get file type from database
                                $fileType = FileType::where('name', $fileExtension)->first();
                                if (empty($fileType)) {
                                    return back()->withInput()
                                        ->withErrors(['unexpected_error' => __('message.wrong_file_type_Please_provide_valid_files')]);
                                }

                                // Store file in site folder
                                $filename = 'logo-' . time() . '.' . $fileExtension;
                                $photo->storeAs('sites', $filename);

                                // update logo settings in db
                                $settingLine = Setting::findOrFail($setting->id);
                                $settingLine->update(array(
                                    'value' => $filename
                                ));
                            }
                        } else {
                            $settingLine = Setting::findOrFail($setting->id);
                            $settingLine->update(array(
                                'value' => $inputList[$setting->constant]
                            ));
                        }
                    }
                }
            }

            return redirect()->route('settings.all')
                ->with('success_message', __('message.setting_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }


}
