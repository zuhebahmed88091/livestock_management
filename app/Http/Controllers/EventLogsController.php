<?php

namespace App\Http\Controllers;

use App\Exports\EventLogExport;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\EventLog;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class EventLogsController extends Controller
{
    /**
     * Display a listing of the event logs.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->eventList($request);
        }

        $eventLogs = EventLog::with('user')->get();
        $users = User::all()->pluck('name','id');
        return view('event_logs.index', compact('eventLogs', 'users'));
    }

    public function getQuery($request)
    {
        $query = EventLog::query()->with('user');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('created_at', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->userId)) {
            $query->where('user_id', $request->userId);
        }

        if (!empty($request->changes)) {
            $query->where('changes', 'like', '%' . $request->changes . '%');
        }

        if (!empty($request->endPoint)) {
            $query->where('end_point', 'like', '%' . $request->endPoint . '%');
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
    public function eventList(Request $request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($eventLog) {
                return view('event_logs.action', compact('eventLog'));
            })
            ->editColumn('user', function ($eventLog) {
                return optional($eventLog->user)->name;
            })
            ->editColumn('changes', function ($eventLog) {
                return ($eventLog->changes);
            })
            ->rawColumns(['action', 'changes'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new EventLogExport($this->getQuery($request)),
            'event-log-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $eventLog = EventLog::with('user')->findOrFail($id);
        $view = view('event_logs.print_details', compact('eventLog'));
        CommonHelper::generatePdf($view->render(), 'event-log-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new event log.
     *
     * @return View
     */
    public function create()
    {
        $users = User::pluck('name','id')->all();
        return view('event_logs.create', compact('users'));
    }

    /**
     * Store a new event log in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            EventLog::create($data);

            return redirect()->route('event_logs.index')
                             ->with('success_message', __('message.event_log_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified event log.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $eventLog = EventLog::with('user')->findOrFail($id);
        return view('event_logs.show', compact('eventLog'));
    }

    /**
     * Show the form for editing the specified event log.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $eventLog = EventLog::findOrFail($id);
        $users = User::pluck('name','id')->all();
        return view('event_logs.edit', compact('eventLog','users'));
    }

    /**
     * Update the specified event log in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $eventLog = EventLog::findOrFail($id);
            $eventLog->update($data);

            return redirect()->route('event_logs.index')
                             ->with('success_message', __('message.event_log_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified event log from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     */
    public function destroy($id)
    {
        try {
            $eventLog = EventLog::findOrFail($id);
            $eventLog->delete();

            return redirect()->route('event_logs.index')
                             ->with('success_message', __('message.event_log_was_successfully_deleted'));
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
            'end_point' => 'required|string|min:1|max:100',
            'changes' => 'required|string|min:1|max:255',

        ]);

        return $data;
    }
}
