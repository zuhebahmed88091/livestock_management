@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('food_history.food_history')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('food_history.food_history')}}</a></li>
        <li class="active">{{__('commons.listing')}}</li>
    </ol>
@endsection

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="box box-default collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">{{__('commons.filter_box')}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                    <i class="fa fa-expand"></i>
                </button>
                <a href="{{ route('foodHistory.index') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                </a>
                @if (App\Helpers\CommonHelper::isCapable('foodHistory.create'))
                    <a href="{{ route('foodHistory.create') }}"
                       class="btn btn-sm btn-success"
                       title="{{__('buttonTitle.create_new_foodHistory')}}">
                        <i class="fa fa-plus"></i> {{__('commons.create')}}
                    </a>
                @endif
            </div>
        </div>

        <div class="box-body d-none">
            <div class="row">
                <div class="col-xs-12">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="inventoryFood_id" class="control-label col-lg-2">{{__('commons.food_name')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="inventoryFood_id" id="inventoryFood_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($inventoryTypes as $inventoryType )
                                        <option value="{{ $inventoryType->id }}">
                                            {{ $inventoryType->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="livestock_id" class="control-label col-lg-2">{{__('commons.batch_name')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="livestock_id" id="livestock_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($liveStocks as $liveStock )
                                        <option value="{{ $liveStock->id }}">
                                            {{ $liveStock->batch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="livestock_type_id" class="control-label col-lg-2">{{__('commons.livestock_type')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="livestock_type_id" id="livestock_type_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($liveStockTypes as $liveStockType)
                                        <option value="{{ $liveStockType->id }}">
                                            {{ $liveStockType->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="shed_id" class="control-label col-lg-2">{{__('commons.shed')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="shed_id" id="shed_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($sheds as $shed )
                                        <option value="{{ $shed->id }}">
                                            {{ $shed->shed_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date" class="control-label col-lg-2">{{__('commons.date')}}</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="date"
                                           type="text"
                                           id="date">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-1 pull-right">
                                <button type="button" id="btnSearch" class="btn btn-primary pull-right">
                                    {{__('commons.go')}}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    <div class="table-responsive hide-quick-search">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
                                <th>{{__('commons.food_name')}}</th>
                                <th>{{__('commons.batch_name')}}</th>
                                <th>{{__('commons.livestock_type')}}</th>
                                <th>{{__('commons.shed_no')}}</th>
                                <th>{{__('commons.consume_quantity')}}</th>
                                <th>{{__('commons.duration')}}</th>
                                <th>{{__('commons.date')}}</th>
                                <th class="text-center mw-100">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

<!-- page script -->
@section('javascript')
    <script>

        $(function () {
            "use strict";
            let dateRangeObj = $('#date');
            let startDate = '';
            let endDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[7, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                     {data: 'inventory_id', name: 'inventory_id'},
                     {data: 'livestock_id', name: 'livestock_id'},
                     {data: 'livestock_type_id', name: 'livestock_type_id'},
                     {data: 'shed_id', name: 'shed_id'},
                     {data: 'consume_quantity', name: 'consume_quantity'},
                     {data: 'duration', name: 'duration'},
                     {data: 'date', name: 'date'},

                    {
                        data: 'action',
                        name: 'action',
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    }
                ],
                "pageLength": 10,
                "pagination": true,
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"orderable": false, "targets": 0}
                ],
                initComplete: function () {
                   
                }
            })

            let dateRangeOptions = {
                opens: 'left',
                showDropdowns: true,
                linkedCalendars: false,
                autoUpdateInput: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    cancelLabel: 'Clear'
                }
            };

            // daterangepicker for purchase date
            dateRangeObj.daterangepicker(dateRangeOptions);
            dateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            dateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                startDate = '';
                endDate = '';
            });

            let getUrlQueries = function() {
                let liveStockTypeId = $('#livestock_type_id').val();
                let shedId = $('#shed_id').val();
                let inventoryFoodId = $('#inventoryFood_id').val();
                let livestockId = $('#livestock_id').val();

                return '?startDate=' + startDate
                    + '&endDate=' + endDate
                    + '&liveStockTypeId=' + liveStockTypeId
                    + '&inventoryFoodId=' + inventoryFoodId
                    + '&livestockId=' + livestockId
                    + '&shedId=' + shedId;

            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('foodHistory.index') }}' + dataTableUrl);
                console.log(dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
