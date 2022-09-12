<?php

namespace App\Http\Controllers;
use App\Exports\ShedExport;
use App\Helpers\CommonHelper;
use App\Models\Shed;
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

class ShedsController extends Controller
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
            return $this->sheds($request);
        }
        $sheds = Shed::get();
        $liveStockTypes = LiveStockType::where('status', 'Active')->get();
        $liveStocks = LiveStock::all();
        
        return view('sheds.index', compact('sheds','liveStockTypes','liveStocks'));
    }
    public function getQuery($request)
    {
        $query = Shed::query()->with('creator');
        if (!empty($request->startDate) && !empty($request->endDate)) {
            $query->whereBetween('purchase_date', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        }

        if (!empty($request->shedId)) {
            $query->where('id', $request->shedId);
        }
        if (!empty($request->livestockTypeId)) {
            $query->where('livestock_type_id', $request->livestockTypeId);
        }
        if (!empty($request->livestockId)) {
            $query->where('livestock_id', $request->livestockId);
        }
        if (!empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        return $query;
    }

    

    public function sheds($request)
    {
        return datatables()->of($this->getQuery($request))
            ->addIndexColumn()
            ->addColumn('action', function ($shed) {
                return view('sheds.action', compact('shed'));
            })
             ->editColumn('created_by', function ($shed) {
                 return optional($shed->creator)->name;
             })

             ->addColumn('livestock_type_id', function ($shed) {
                return optional(optional($shed->liveStock)->livestockType)->title;
            })
            
              ->editColumn('livestock_id', function ($shed) {
                return optional($shed->liveStock)->batch_name;
             })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function exportXLSX(Request $request)
    {
        return Excel::download(
            new ShedExport($this->getQuery($request)),
            'sheds-' . time() . '.xlsx'
        );
    }

    public function printDetails($id)
    {
        set_time_limit(300);
        $shed = Shed::with('liveStock','creator','livestockType')->findOrFail($id);
        $view = view('sheds.print_details', compact('shed'));
        CommonHelper::generatePdf($view->render(), 'Shed-details-' . date('Ymd'));
    }
    /**
     * Show the form for creating a new livestock type.
     *
     * @return View
     */

    public function create()
    {
        $shed = Shed::all();
        $liveStocks = LiveStock::all();
        return view('sheds.create', compact('shed','liveStocks'));
    }

    /**
     * Store a new livestock type in the storage.
     *
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function store(Request $request)
    {
        $data = $this->getData($request);
        try {
            $data['created_by'] = Auth::user()->id;
            Shed::create($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'sheds.create',
                    'changes' => EventHelper::logForCreate($data)
                ]);
            }

            return redirect()->route('sheds.index')
                             ->with('success_message',  __('message.sheds_was_successfully_added'));
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
        $shed = Shed::with('creator','liveStock')->findOrFail($id);
        
        return view('sheds.show', compact('shed'));
    }

    /**
     * Show the form for editing the specified livestock type.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $shed = Shed::with('creator')->findOrFail($id);
        $liveStocks = LiveStock::all();

        return view('sheds.edit', compact('shed','liveStocks'));
    }
     /**
     * Update the specified livestock type in the storage.
     *
     * @param  int $id
     * @param Request $request
     * @return RedirectResponse | Redirector
     */
    public function update($id, Request $request)
    {
        $data = $this->getData($request, $id);
        try {
            $shed = Shed::findOrFail($id);
            $oldData = $shed->toArray();
            $shed->update($data);

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'sheds.update',
                    'changes' => EventHelper::logForUpdate($oldData, $data)
                ]);
            }

            return redirect()->route('sheds.index')
                             ->with('success_message', __('message.sheds_was_successfully_updated'));
        } catch (Exception $exception) {
            return back()->withInput()
                         ->withErrors(['unexpected_error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $shed = Shed::findOrFail($id);
            $oldData = $shed->toArray();
            $shed->delete();

            if (config('settings.IS_EVENT_LOGS_ENABLE')) {
                EventLog::create([
                    'user_id' => Auth::user()->id,
                    'end_point' => 'sheds.destroy',
                    'changes' => EventHelper::logForDelete($oldData)
                ]);
            }

            return redirect()->route('sheds.index')
                             ->with('success_message', __('message.sheds_was_successfully_deleted'));
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
    protected function getData(Request $request, $id=0)
    {
        $data = $request->validate([
            'livestock_id' => 'required',
            'shed_no' => 'required|string|min:1|max:100|unique:sheds,shed_no,' . $id . ',id',
            'purchase_date' => 'required',
            'age' => 'required',
            'quantity' => 'required',
            'status' => 'required',
            'comments' => 'required'

            
        ]);

        $data['shed_no'] = clean($request->shed_no);
        $data['purchase_date'] = clean($request->purchase_date);
        $data['age'] = clean($request->age);
        $data['quantity'] = clean($request->quantity);
        $data['comments'] = clean($request->comments);
        

        return $data;
    }
}
