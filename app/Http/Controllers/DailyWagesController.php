<?php

namespace App\Http\Controllers;

use App\Exports\DailyWageExport;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\DailyWage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Models\EventLog;
use App\Helpers\EventHelper;
use Maatwebsite\Excel\Facades\Excel;

class DailyWagesController extends Controller
{
    /**
     * Display a listing of the daily wages.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->dailyWageList($request);
        }

        $dailyWages = DailyWage::with('user')->get();
        $dayLabours = User::whereHas('roles', function ($q) {
            $q->where('name', 'Day-Labour');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('daily_wages.index', compact('dailyWages', 'dayLabours'));
    }

    public function getQuery($request)
    {
        $query = DailyWage::query()->with('user');
        if (!empty($request->workStartDate) && !empty($request->workEndDate)) {
            $query->whereBetween('work_date', [
                $request->workStartDate . ' 00:00:00',
                $request->workEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->userId)) {
            $query->where('user_id', $request->userId);
        }

        if (isset($request->wages) && !empty($request->opWages)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'wages',
                $request->opWages,
                $request->wages
            );
        }

        if (!empty($request->payingStatus)) {
            $query->where('paying_status', $request->payingStatus);
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
    public function dailyWageList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($dailyWage) {
                return view('daily_wages.action', compact('dailyWage'));
            })
            ->editColumn('user_id', function ($dailyWage) {
                return optional($dailyWage->user)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new DailyWageExport($this->getQuery($request)),
            'daily-wages-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $dailyWage = DailyWage::with('user')->findOrFail($id);
        $view = view('daily_wages.print_details', compact('dailyWage'));
        CommonHelper::generatePdf($view->render(), 'Daily-wage-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new daily wage.
     *
     * @return View
     */
    public function create()
    {
        $users = $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'Day-Labour');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('daily_wages.create', compact('users'));
    }

    /**
     * Store a new daily wage in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            DailyWage::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'daily_wages.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('daily_wages.index')
                             ->with('success_message', __('message.daily_wage_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified daily wage.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $dailyWage = DailyWage::with('user')->findOrFail($id);
        return view('daily_wages.show', compact('dailyWage'));
    }

    /**
     * Show the form for editing the specified daily wage.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $dailyWage = DailyWage::findOrFail($id);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'Day-Labour');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('daily_wages.edit', compact('dailyWage','users'));
    }

    /**
     * Update the specified daily wage in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {
            $dailyWage = DailyWage::findOrFail($id);
            $oldData = $dailyWage->toArray();
            $dailyWage->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'daily_wages.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('daily_wages.index')
                             ->with('success_message', __('message.daily_wage_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified daily wage from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $dailyWage = DailyWage::findOrFail($id);
            $oldData = $dailyWage->toArray();
            $dailyWage->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'daily_wages.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('daily_wages.index')
                             ->with('success_message', __('message.daily_wage_was_successfully_deleted'));
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
            'user_id' => 'required',
            'wages' => 'required|numeric|min:-2147483648|max:2147483647',
            'paying_status' => 'required',
            'work_date' => 'required|date',
            'comments' => 'nullable'
        ]);

        $data['comments'] = clean($request->comments);

        return $data;
    }
}
