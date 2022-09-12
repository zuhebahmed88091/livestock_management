@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('livestocks.livestocks')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('livestocks.livestocks')}}</a></li>
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
                <a href="{{ route('livestocks.index') }}" class="btn btn-info btn-sm">
                    <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                </a>
                @if (App\Helpers\CommonHelper::isCapable('livestocks.create'))
                    <a href="{{ route('livestocks.create') }}"
                       class="btn btn-sm btn-success"
                       title="{{__('buttonTitle.create_new_livestock')}}">
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
                            <label for="livestock_type_id" class="control-label col-lg-2">{{__('commons.livestock_type')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="livestock_type_id" id="livestock_type_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($liveStockTypes as $liveStockType )
                                        <option value="{{ $liveStockType->id }}">
                                            {{ $liveStockType->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <label for="date" class="control-label col-lg-2">{{__('commons.start_date')}}</label>
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
                            <label for="date" class="control-label col-lg-2">{{__('commons.expected_sale_date')}}</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="sale_date"
                                           type="text"
                                           id="sale_date">
                                </div>
                            </div>

                            <label for="status" class="control-label col-lg-2">{{__('commons.status')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="status" id="status">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    <option value="Active" selected>{{__('commons.status_active')}}</option>
                                    <option value="Inactive">{{__('commons.inactive')}}</option>
                                    <option value="Sold">{{__('commons.sold')}}</option>
                                </select>
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
                                <th>{{__('commons.batch_name')}}</th>
                                <th>{{__('commons.livestock_type')}}</th>
                                <th>{{__('commons.quantity')}}</th>
                                <th>{{__('commons.start_date')}}</th>
                                <th>{{__('commons.expected_sale_date')}}</th>
                                <th>{{__('commons.status')}}</th>
                                <th>{{__('commons.created_by')}}</th>
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
            let saleDateRangeObj = $('#sale_date');
            let startDate = '';
            let endDate = '';
            let saleStartDate = '';
            let saleEndDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[4, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'batch_name', name: 'batch_name'},
                    {data: 'livestock_type_id', name: 'livestock_type_id'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'date', name: 'date'},
                    {data: 'sale_date', name: 'sale_date'},
                    {data: 'status', name: 'status'},
                    {data: 'created_by', name: 'created_by'},
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
            dateRangeObj.daterangepicker(dateRangeOptions);
            saleDateRangeObj.daterangepicker(dateRangeOptions);
            dateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            saleDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                saleStartDate = picker.startDate.format('YYYY-MM-DD');
                saleEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            dateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                startDate = '';
                endDate = '';
            });
            saleDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                saleStartDate = '';
                saleEndDate = '';
            });

            let getUrlQueries = function() {
                let liveStockTypeId = $('#livestock_type_id').val();
                let status = $('#status').val();
                
                
                return '?startDate=' + startDate
                    + '&endDate=' + endDate
                    + '&saleStartDate=' + saleStartDate
                    + '&saleEndDate=' + saleEndDate
                    + '&status=' + status
                    + '&liveStockTypeId=' + liveStockTypeId;
                    
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('livestocks.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
