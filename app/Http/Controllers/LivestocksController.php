<?php

namespace App\Http\Controllers;
use App\Exports\LiveStockExport;
use App\Helpers\CommonHelper;
use App\Models\LiveStock;
use App\Models\LiveStockType;
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

class LiveStocksController extends Controller
{
    /**
     * Display a listing of the livestocks.Query??
     *
     * @param Request $request
     * @return View | Response
     * @throws Exception
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->liveStocks($request);
        }
        $liveStocks = LiveStock::get();
        $liveStockTypes = LiveStockType::where('status', 'Active')->get();
        return view('livestocks.index', compact('liveStocks','liveStockTypes'));
    }
    public function getQuery($request)
    {
        $query = LiveStock::query()->with('creator');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('date', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }
        if (!empty($request->saleStartDate) && !empty($request->saleEndDate)) {
            $query->whereBetween('sale_date', [
                $request->saleStartDate . ' 00:00:00',
                $request->saleEndDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->liveStockTypeId)) {
            $query->where('livestock_type_id', $request->liveStockTypeId);
        }
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }

        return $query;
    }

    public function liveStocks($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($liveStock) {
                return view('livestocks.action', compact('liveStock'));
            })
            ->editColumn('created_by', function ($liveStock) {
                return optional($liveStock->creator)->name;
            })
            ->editColumn('livestock_type_id', function ($liveStock) {
                return optional($liveStock->livestockType)->title;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new LiveStockExport($this->getQuery($request)),
            'livestocks-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $liveStock = LiveStock::findOrFail($id);
        $view = view('livestocks.print_details', compact('liveStock'));
        CommonHelper::generatePdf($view->render(), 'Livestock-details-' . date('Ymd'));
    }
    /**
     * Show the form for creating a new livestock.
     *
     * @return View
     */

    public function create()
    {
        $liveStockTypes = LiveStockType::where('status', 'Active')->get();
        return view('livestocks.create', compact('liveStockTypes'));

    }

    /**
     * Store a new livestock in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            LiveStock::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'livestocks.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('livestocks.index')
                             ->with('success_message',  __('message.livestocks_was_successfully_added'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified livestock.
     *
     * @param int $id
     * @return View
     */
    public function show($id)
    {
        $liveStock = LiveStock::findOrFail($id);
        return view('livestocks.show', compact('liveStock'));
    }

    /**
     * Show the form for editing the specified livestock.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $liveStock = LiveStock::findOrFail($id);
        $liveStockTypes = LiveStockType::where('status', 'Active')->get();
        return view('livestocks.edit', compact('liveStock','liveStockTypes'));
    }
     /**
     * Update the specified livestock in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request, $id);
        try {
            $liveStock = LiveStock::findOrFail($id);
            $oldData = $liveStock->toArray();
            $liveStock->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'livestocks.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('livestocks.index')
                             ->with('success_message', __('message.livestocks_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $liveStock = LiveStock::findOrFail($id);
            $oldData = $liveStock->toArray();
            $liveStock->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'livestocks.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('livestocks.index')
                             ->with('success_message', __('message.livestocks_was_successfully_deleted'));
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
    protected function getData(Request $request, $id = 0)
    {
        $data = $request->validate([
            'batch_name' => 'required|string|min:1|max:100|unique:livestocks,batch_name,' . $id . ',id',
            'livestock_type_id' => 'required',
            'date' => 'required',
            'sale_date' => 'required',
            'status' => 'required',
            'quantity' => 'required',
            'comments' => 'required',
            

        ]);
        

        $data['livestock_type_id'] = clean($request->livestock_type_id);
        $data['date'] = clean($request->date);
        $data['sale_date'] = clean($request->sale_date);
        $data['batch_name'] = clean($request->batch_name);
        $data['quantity'] = clean($request->quantity);
        $data['comments'] = clean($request->comments);
        

        return $data;
    }
}
