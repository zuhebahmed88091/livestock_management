<?php

namespace App\Http\Controllers;

use App\Exports\LeaveExport;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\Leave;
use Dompdf\Dompdf;
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

class LeavesController extends Controller
{
    /**
     * Display a listing of the leaves.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->leaveList($request);
        }

        $leaves = Leave::with('user')->get();
        $employees = User::whereHas('roles', function ($q) {
            $q->where('name', 'Employee');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('leaves.index', compact('leaves', 'employees'));
    }

    public function getQuery($request)
    {
        $query = Leave::query()->with('user');
        if (!empty($request->leaveStartDate) && !empty($request->leaveEndDate)) {
            $query->where('start_date', '>=', $request->leaveStartDate);
            $query->where('end_date', '<=', $request->leaveEndDate);
        }

        if (!empty($request->userId)) {
            $query->where('user_id', $request->userId);
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
    public function leaveList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($leave) {
                return view('leaves.action', compact('leave'));
            })
            ->addColumn('days', function ($leave) {
                $start = strtotime($leave->start_date);
                $end = strtotime($leave->end_date);
                return ceil(abs($end - $start) / 86400);
            })
            ->editColumn('user_id', function ($leave) {
                return optional($leave->user)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new LeaveExport($this->getQuery($request)),
            'leaves-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $leave = Leave::with('user')->findOrFail($id);
        $view = view('leaves.print_details', compact('leave'));
        CommonHelper::generatePdf($view->render(), 'Leave-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new leave.
     *
     * @return View
     */
    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'Employee');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('leaves.create', compact('users'));
    }

    /**
     * Store a new leave in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            Leave::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'leaves.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('leaves.index')
                             ->with('success_message', __('message.leave_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified leave.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $leave = Leave::with('user')->findOrFail($id);
        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified leave.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'Employee');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('leaves.edit', compact('leave','users'));
    }

    /**
     * Update the specified leave in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $leave = Leave::findOrFail($id);
            $oldData = $leave->toArray();
            $leave->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'leaves.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('leaves.index')
                             ->with('success_message', __('message.leave_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified leave from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws Exception
     */
    public function destroy($id)
    {
        try {
            $leave = Leave::findOrFail($id);
            $oldData = $leave->toArray();
            $leave->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'leaves.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('leaves.index')
                             ->with('success_message', __('message.leave_was_successfully_deleted'));
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
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'comments' => 'nullable',
        ]);

        $data['comments']  = clean($request->comments);

        return $data;
    }
}
