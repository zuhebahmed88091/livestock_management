@extends('layouts.app')

@section('content-header')
    <h1>{{__('info.dashboard')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('info.home')}}</a></li>
        <li class="active">{{__('info.dashboard')}}</li>
    </ol>
@endsection

@section('content')

    <!-- Info boxes -->
    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('livestocks.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-paw"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_livestocks')}}</span>
                        <span class="info-box-number">{{ $totalLiveStocks }}</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('livestocks_types.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-th-large"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_livestock_types')}}</span>
                        <span class="info-box-number">{{ $totalLiveStockTypes }}</span>
                    </div>
                </div>
            </a>
        </div>

        @if (App\Helpers\CommonHelper::isCapable('dashboard.reportTotalIncome'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('ledgers.index') }}" class="info-link">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{__('info.total_income')}}</span>
                            <span class="info-box-number">{{ $totalIncome }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

    <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        @if (App\Helpers\CommonHelper::isCapable('dashboard.reportTotalExpense'))
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ route('ledgers.index') }}" class="info-link">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-money"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{__('info.total_expense')}}</span>
                            <span class="info-box-number">{{ $totalExpense }}</span>
                        </div>
                    </div>
                </a>
            </div>
        @endif

        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('inventories.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-cubes"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_inventories')}}</span>
                        <span class="info-box-number">{{ $totalInventory }}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('daily_wages.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_employees')}}</span>
                        <span class="info-box-number">{{ $totalEmployee }}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('sheds.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-home"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_sheds')}}</span>
                        <span class="info-box-number">{{ $totalSheds }}</span>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <a href="{{ route('foodHistory.index') }}" class="info-link">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-sitemap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">{{__('info.total_food_histories ')}}</span>
                        <span class="info-box-number">{{ $totalFood }}</span>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row equal padding-bottom-15">
        {{-- @if (App\Helpers\CommonHelper::isCapable('dashboard.reportIncomeExpenseChart')) --}}
        <div class="col-md-7">
            <!-- BAR CHART -->
            <div class="box box-success h-100">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('info.monthly_Income_expense_report')}}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="barChart" class="h-280"></canvas>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        {{-- @endif --}}

        <div class="col-md-5">
            <div class="box box-default h-100">

                <div class="box-header with-border">
                    <h3 class="box-title">{{__('info.livestock_by_types')}}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="chart-responsive">
                                <canvas id="pieChart" height="260"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <ul class="chart-legend clearfix">
                                @foreach($livestockTypes as $livestockType)
                                    <li>
                                        <i class="fa fa-circle-o" style="color: {!! $livestockType->color !!}"></i>
                                        {{ $livestockType->title }} ({{ $livestockType->livestock_count }})
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

<!-- page script -->
@section('javascript')
    <script>
        let barChartLabel = [{!! $barChartLabel !!}];
        let monthlyIncomes = [{{ $monthlyIncomes }}];
        let monthlyExpenses = [{{ $monthlyExpenses }}];
        let pieChartData = JSON.parse('{!! $pieChart !!}');

    </script>
    <script src="{{ asset('chart.js/Chart.js') }}"></script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
