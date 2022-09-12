@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('medicine.medicines')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('medicine.medicines')}}</a></li>
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

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">{{__('commons.filter_box')}}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-sm btn-default" data-widget="collapse">
                    <i class="fa fa-compress"></i>
                </button>
                <a href="{{ route('medicines.index') }}" class="btn btn-info btn-sm" title="Collapse">
                    <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                </a>
                @if (App\Helpers\CommonHelper::isCapable('medicines.create'))
                    <a href="{{ route('medicines.create') }}"
                       class="btn btn-sm btn-success"
                       title="{{__('buttonTitle.create-new_medicine')}}">
                        <i class="fa fa-plus"></i> {{__('commons.create')}}
                    </a>
                @endif
            </div>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-xs-12">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="livestock_id" class="control-label col-lg-2">{{__('commons.batch_name')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="livestock_id" id="livestock_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($liveStocks as $liveStock)
                                        <option value="{{ $liveStock->id }}">
                                            {{ $liveStock->batch_name}}
                                            
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <label for="livestock_type_id" class="control-label col-lg-2">{{__('commons.livestock_type')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="livestock_type_id" id="livestock_type_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($liveStockTypes as $liveStockType)
                                        <option value="{{ $liveStockType->id }}">
                                            {{ $liveStockType->title}}
                                            
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        

                        <div class="form-group">

                            <label for="identify_date" class="control-label col-lg-2">{{__('commons.identify_date')}}</label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="identify_date"
                                           type="text"
                                           id="identify_date">
                                </div>
                            </div>

                            <label for="doctor_id" class="control-label col-lg-2">{{__('medicine.doctor')}}</label>
                            <div class="col-lg-4">
                                <select class="form-control" name="doctor_id" id="doctor_id">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    @foreach ($doctors as $key => $title)
                                        <option value="{{ $key }}">
                                            {{ $title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="next_follow_up_date" class="control-label col-lg-2">
                                {{__('commons.next_follow_up_date')}}
                            </label>
                            <div class="col-lg-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input class="form-control date-range-picker" name="next_follow_up_date"
                                           type="text"
                                           id="next_follow_up_date">
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
                                <th>{{__('commons.shed_no')}}</th>
                                <th>{{__('medicine.doctor')}}</th>
                                <th>{{__('commons.identify_date')}}</th>
                                <th>{{__('commons.start_date')}}</th>
                                <th>{{__('commons.end_date')}}</th>
                                <th>{{__('commons.next_follow_up_date')}}</th>
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
            let identifyDateRangeObj = $('#identify_date');
            let identifyStartDate = '';
            let identifyEndDate = '';

            let nextFollowUpDateRangeObj = $('#next_follow_up_date');
            let nextFollowUpStartDate = '';
            let nextFollowUpEndDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[6, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'livestock_id', name: 'livestock_id'},
                    {data: 'livestock_type_id', name: 'livestock_type_id'},
                    {data: 'shed_id', name: 'shed_id'},
                    {data: 'doctor', name: 'doctor'},
                    {data: 'identify_date', name: 'identify_date'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'next_follow_up_date', name: 'next_follow_up_date'},
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
            dateRangeOptions.opens = 'right';
            identifyDateRangeObj.daterangepicker(dateRangeOptions);
            identifyDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                identifyStartDate = picker.startDate.format('YYYY-MM-DD');
                identifyEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            identifyDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                identifyStartDate = '';
                identifyEndDate = '';
            });

            // daterangepicker for purchase date
            dateRangeOptions.opens = 'left';
            nextFollowUpDateRangeObj.daterangepicker(dateRangeOptions);
            nextFollowUpDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                nextFollowUpStartDate = picker.startDate.format('YYYY-MM-DD');
                nextFollowUpEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            nextFollowUpDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                nextFollowUpStartDate = '';
                nextFollowUpEndDate = '';
            });

            let getUrlQueries = function() {
                let livestockTypeId = $('#livestock_type_id').val();
                let livestockId = $('#livestock_id').val();
                let doctorId = $('#doctor_id').val();
                return '?identifyStartDate=' + identifyStartDate
                    + '&identifyEndDate=' + identifyEndDate
                    + '&nextFollowUpStartDate=' + nextFollowUpStartDate
                    + '&nextFollowUpEndDate=' + nextFollowUpEndDate
                    + '&livestockTypeId=' + livestockTypeId
                    + '&livestockId=' + livestockId
                    + '&doctorId=' + doctorId;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                console.log(dataTableUrl);
                dataTable.ajax.url('{{ route('medicines.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
