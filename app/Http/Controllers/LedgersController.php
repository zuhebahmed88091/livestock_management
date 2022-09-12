<?php

namespace App\Http\Controllers;

use App\Exports\LedgerExport;
use App\Helpers\CommonHelper;
use App\Models\Tag;
use App\Models\Ledger;
use DB;
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

class LedgersController extends Controller
{
    /**
     * Display a listing of the ledgers.
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->ledgerList($request);
        }

        $ledgers = Ledger::with('tag')->get();
        $tags = Tag::where('status', 'Active')->pluck('title','id');
        return view('ledgers.index', compact('ledgers', 'tags'));
    }

    public function getQuery($request)
    {
        $query = Ledger::query()->with('tag');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('date', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->type)) {
            $query->where('type', $request->type);
        }

        if (isset($request->amount) && !empty($request->opAmount)) {
            CommonHelper::setIntFilterQuery(
                $query,
                'amount',
                $request->opAmount,
                $request->amount
            );
        }

        if (!empty($request->tagId)) {
            $query->where('tag_id', $request->tagId);
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
    public function ledgerList($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($ledger) {
                return view('ledgers.action', compact('ledger'));
            })
            ->editColumn('tag', function ($ledger) {
                return optional($ledger->tag)->title;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getBalanceSheetTable($request, $isPrint = false)
    {
        $totalCredit = 0;
        $totalDebit = 0;
        $cumulativeSum = 0;
        $previousBalance = 0;

        $query = Ledger::query()->with('tag');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('date', [$request->startDate, $request->endDate]);

            $income = Ledger::where([
                ['date', '<', $request->startDate],
                ['type', 'Income'],
            ])->sum('amount');

            $expense = Ledger::where([
                ['date', '<', $request->startDate],
                ['type', 'Expense'],
            ])->sum('amount');

            $previousBalance = $income - $expense;
            $cumulativeSum = $previousBalance;
        }
        $ledgers = $query->get();

        foreach ($ledgers as $ledger) {
            $ledger->credit = 0;
            $ledger->debit = 0;
            if ($ledger->type == 'Income') {
                $ledger->credit = $ledger->amount;
                $cumulativeSum += $ledger->amount;
                $ledger->cumulativeSum = $cumulativeSum;
                $totalCredit += $ledger->amount;
            } else if ($ledger->type == 'Expense') {
                $ledger->debit = $ledger->amount;
                $cumulativeSum -= $ledger->amount;
                $ledger->cumulativeSum = $cumulativeSum;
                $totalDebit += $ledger->amount;
            }
        }

        $viewFile = 'ledgers.balance_sheet_table';
        if ($isPrint) {
            $viewFile = 'ledgers.print_balance_sheet';
        }

        return view($viewFile, compact(
            'ledgers', 'totalCredit', 'totalDebit', 'cumulativeSum', 'previousBalance'
        ));
    }

    public function balanceSheet(Request $request)
    {
        if ($request->ajax()) {
            return $this->getBalanceSheetTable($request);
        }

        return view('ledgers.balance_sheet');
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new LedgerExport($this->getQuery($request)),
            'ledgers-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $ledger = Ledger::with('tag','creator')->findOrFail($id);
        $view = view('ledgers.print_details', compact('ledger'));
        CommonHelper::generatePdf($view->render(), 'Ledger-details-' . date('Ymd'));
    }

    public function printBalanceSheet(Request $request)
    {
        set_time_limit(300);
        ini_set('memory_limit', '-1');
        $view = $this->getBalanceSheetTable($request, true);
        CommonHelper::generatePdf($view->render(), 'Balance-sheet-' . date('Ymd'));
    }

    /**
     * Show the form for creating a new ledger.
     *
     * @return View
     */
    public function create()
    {
        $tags = Tag::where('status', 'Active')->pluck('title','id');
        return view('ledgers.create', compact('tags'));
    }

    /**
     * Store a new ledger in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Ledger::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'ledgers.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('ledgers.index')
                             ->with('success_message', __('message.ledger_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified ledger.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $ledger = Ledger::with('tag','creator')->findOrFail($id);
        return view('ledgers.show', compact('ledger'));
    }

    /**
     * Show the form for editing the specified ledger.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $ledger = Ledger::findOrFail($id);
        $tags = Tag::where('status', 'Active')->pluck('title','id');
        return view('ledgers.edit', compact('ledger','tags'));
    }

    /**
     * Update the specified ledger in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request);
        try {

            $ledger = Ledger::findOrFail($id);
            $oldData = $ledger->toArray();
            $ledger->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'ledgers.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('ledgers.index')
                             ->with('success_message', __('message.ledger_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified ledger from the storage.
     *
     * @param  int $id
     * @return RedirectResponse | Redirector
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {
            $ledger = Ledger::findOrFail($id);
            $oldData = $ledger->toArray();
            $ledger->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'ledgers.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('ledgers.index')
                             ->with('success_message', __('message.ledger_was_successfully_deleted'));
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
            'type' => 'required',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:-2147483648|max:2147483647',
            'tag_id' => 'required',
            'details' => 'required',
        ]);
        $data['details']  = clean($request->details);
        return $data;
    }
}
