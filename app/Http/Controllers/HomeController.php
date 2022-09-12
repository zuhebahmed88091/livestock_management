<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Shed;
use App\Models\Ledger;
use App\Models\DailyWage;
use App\Models\Inventory;
use App\Models\LiveStock;
use App\Models\FoodHistory;
use App\Helpers\CommonHelper;
use App\Models\LiveStockType;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['page_title'] = 'Page Title';
        $data['page_description'] = '';
        $data['tasks'] = [
            [
                'name' => 'Design New Dashboard',
                'progress' => '87',
                'color' => 'danger'
            ],
            [
                'name' => 'Create Home Page',
                'progress' => '76',
                'color' => 'warning'
            ],
            [
                'name' => 'Some Other Task',
                'progress' => '32',
                'color' => 'success'
            ],
            [
                'name' => 'Start Building Website',
                'progress' => '56',
                'color' => 'info'
            ],
            [
                'name' => 'Develop an Awesome Algorithm',
                'progress' => '10',
                'color' => 'success'
            ]
        ];

        return view('home')->with($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return RedirectResponse | Redirector
     */
    public function dashboard()
    {
        $totalLiveStocks = LiveStock::all()->count();
        $totalLiveStockTypes = LiveStockType::all()->count();
        $totalFood = FoodHistory::all()->count();
        $totalIncome = Ledger::where('type', 'Income')->sum('amount');
        $totalExpense = Ledger::where('type', 'Expense')->sum('amount');
        $totalInventory = Inventory::all()->count();
        $totalEmployee = DailyWage::all()->count();
        $totalSheds = Shed::all()->count();

        $data['totalLiveStocks'] = CommonHelper::numberFormatIndia($totalLiveStocks);
        $data['totalLiveStockTypes'] = CommonHelper::numberFormatIndia($totalLiveStockTypes);
        $data['totalFood'] = CommonHelper::numberFormatIndia($totalFood);
        $data['totalIncome'] = CommonHelper::numberFormatIndia($totalIncome);
        $data['totalExpense'] = CommonHelper::numberFormatIndia($totalExpense);
        $data['totalInventory'] = CommonHelper::numberFormatIndia($totalInventory);
        $data['totalEmployee'] = CommonHelper::numberFormatIndia($totalEmployee);
        $data['totalSheds'] = CommonHelper::numberFormatIndia($totalSheds);

        // Bar chart data
        $barChartLabel = '';
        $monthlyIncomes = [];
        $monthlyExpenses = [];
        for ($i = 5; $i > -1; $i--) {
            if ($barChartLabel) {
                $barChartLabel .= ',';
            }
            $barChartLabel .= '"' . date('F', strtotime("-$i month")) . '"';

            $month = date('Y-m', strtotime("-$i month"));
            $monthlyIncomes[] = Ledger::where('type', 'Income')
                ->where('date', 'like', '%' . $month . '%')
                ->sum('amount');

            $monthlyExpenses[] = Ledger::where('type', 'Expense')
                ->where('date', 'like', '%' . $month . '%')
                ->sum('amount');
        }

        $data['barChartLabel'] = $barChartLabel;
        $data['monthlyIncomes'] = implode(',', $monthlyIncomes);
        $data['monthlyExpenses'] = implode(',', $monthlyExpenses);

        // Pie chart data
        $cnt = 0;
        $pieChart = [];
        $colors = [
            '#f56954',
            '#00a65a',
            '#f39c12',
            '#00c0ef',
            '#3c8dbc',
            '#d2d6de',
            '#5E85F3',
            '#F5A8C1',
            '#82A69D',
            '#F3CCC5',
            '#BAEFEC',
            '#DEB0DE'
        ];
        $livestockTypes = LiveStockType::where('status', 'Active')
            ->withCount('livestock')
            ->orderBy('livestock_count', 'DESC')
            ->get();
        foreach ($livestockTypes as $livestockType) {
            $color = $colors[$cnt % 12];
            $livestockType->color = $color;
            $pieChart[] = (object)[
                'value' => $livestockType->livestock_count,
                'color' => $color,
                'highlight' => $color,
                'label' => htmlentities($livestockType->title)
            ];

            $cnt++;
        }

        $data['pieChart'] = json_encode($pieChart);
        $data['livestockTypes'] = $livestockTypes;

        return view('dashboard')->with($data);
    }

    public function media()
    {
        return view('media');
    }
}
