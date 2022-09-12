@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('inventory.inventory_stocks')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('inventory.inventory_stocks')}}</a></li>
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

    <div class="row">
        <div class="col-xs-12">

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('commons.filter_box')}}</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                            <i class="fa fa-compress"></i>
                        </button>
                        <a href="{{ route('inventory_stocks.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                    </div>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-12">

                            <form id="formSearch" method="POST" class="form-horizontal">
                                <div class="form-group">
                                    <label for="inventory_id" class="control-label col-lg-2">{{__('inventory.inventory')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="inventory_id" id="inventory_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($inventories as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label for="inventory_date" class="control-label col-lg-2">{{__('inventory.inventory_date')}}</label>
                                    <div class="col-lg-3">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control date-range-picker" name="inventory_date"
                                                   type="text"
                                                   id="inventory_date">
                                        </div>
                                    </div>

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
                                <th class="text-center">{{__('commons.image')}}</th>
                                <th>{{__('inventory.inventory')}}</th>
                                <th class="text-right">{{__('inventory.total_amount')}}</th>
                                <th class="text-right">{{__('inventory.consume_amount')}}</th>
                                <th class="text-right">{{__('inventory.stock_amount')}}</th>
                                <th class="text-right">{{__('inventory.total_cost')}}</th>
                                <th>{{__('commons.created_by')}}</th>
                                <th class="text-center mw-100">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

<!-- page script -->
@section('javascript')
    <script src="{{ asset('bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            "use strict";
            // Date range as a button
            let dateRangeBtnObj = $('#inventory_date');
            let startDate = '';
            let endDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[2, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'inventory_image', name: 'inventory_image', className: "text-center"},
                    {data: 'name', name: 'name'},
                    {data: 'totalStockQuantity', name: 'totalStockQuantity', className: "text-right"},
                    {data: 'totalConsumeQuantity', name: 'totalConsumeQuantity', className: "text-right"},
                    {data: 'currentStockQuantity', name: 'currentStockQuantity', className: "text-right"},
                    {data: 'totalStockCost', name: 'totalStockCost', className: "text-right"},
                    {data: 'addedBy', name: 'addedBy'},
                    {data: 'action', name: 'action', className: "text-center", orderable: false, searchable: false}
                ],
                "pageLength": 10,
                "pagination": true,
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"orderable": false, "targets": 0},
                    {"orderable": false, "targets": 1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(`{!! view('commons.button') !!}`);

                    $('#btnExportXLSX').on('click',function () {
                        location.href = '{{ route('inventory_stocks.exportXLSX') }}' + dataTableUrl;
                    });
                }
            });

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
            dateRangeBtnObj.daterangepicker(dateRangeOptions);
            dateRangeBtnObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format('MMM D, YYYY'));
            });
            dateRangeBtnObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                startDate = '';
                endDate = '';
            });

            let getUrlQueries = function() {
                let inventoryId = $('#inventory_id').val();
                let quantity = $('#quantity').val();
                let opQuantity = $('#op_quantity').val();
                let cost = $('#cost').val();
                let opCost = $('#op_cost').val();
                return '?startDate=' + startDate
                    + '&endDate=' + endDate
                    + '&inventoryId=' + inventoryId;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('inventory_stocks.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });
    </script>
@endsection
