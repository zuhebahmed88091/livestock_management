<?php

namespace App\Http\Controllers;

use App\Exports\SalaryExport;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\Salary;
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

class SalariesController extends Controller
{
    /**
     * Display a listing of the salaries.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->salaryList($request);
        }

        $salaries = Salary::with('user')->get();
        $employees = User::whereHas('roles', function ($q) {
            $q->where('name', 'Employee');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('salaries.index', compact('salaries', 'employees'));
    }

    public function getQuery($request)
    {
        $query = Salary::query()->with('user');
        if (!empty($request->salaryStartDate) && !empty($request->salaryEndDate)) {
            $query->whereBetween('salary_date', [
                $request->salaryStartDate . ' 00:00:00',
                $request->salaryEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->userId)) {
            $query->where('user_id', $request->userId);
        }

        if (isset($request->amount) && !empty($request->opAmount)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'amount',
                $request->opAmount,
                $request->amount
            );
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
    public function salaryList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($salary) {
                return view('salaries.action', compact('salary'));
            })
            ->editColumn('user_id', function ($salary) {
                return optional($salary->user)->name;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new SalaryExport($this->getQuery($request)),
            'salary-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $salary = Salary::with('user')->findOrFail($id);
        $view = view('salaries.print_details', compact('salary'));
        CommonHelper::generatePdf($view->render(), 'Salary-details-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new salary.
     *
     * @return View
     */
    public function create()
    {
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'Employee');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('salaries.create', compact('users'));
    }

    /**
     * Store a new salary in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {

            Salary::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'salaries.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('salaries.index')
                             ->with('success_message', __('message.salary_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified salary.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $salary = Salary::with('user')->findOrFail($id);
        return view('salaries.show', compact('salary'));
    }

    /**
     * Show the form for editing the specified salary.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $salary = Salary::findOrFail($id);
        $users = User::whereHas('roles', function ($q) {
            $q->where('name', 'Employee');
        })->orderBy('name', 'ASC')->pluck('name', 'id');
        return view('salaries.edit', compact('salary','users'));
    }

    /**
     * Update the specified salary in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $salary = Salary::findOrFail($id);
            $oldData = $salary->toArray();
            $salary->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'salaries.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('salaries.index')
                             ->with('success_message', __('message.salary_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified salary from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $salary = Salary::findOrFail($id);
            $oldData = $salary->toArray();
            $salary->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'salaries.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('salaries.index')
                             ->with('success_message', __('message.salary_was_successfully_deleted'));
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
            'salary_date' => 'required|date',
            'amount' => 'required|numeric|min:-2147483648|max:2147483647',
            'comments' => 'nullable',

        ]);
        $data['comments']  = clean($request->comments);
        return $data;
    }
}
