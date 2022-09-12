@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('employees.daily_wages')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('employees.daily_wages')}}</a></li>
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
                        <a href="{{ route('daily_wages.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('daily_wages.create'))
                            <a href="{{ route('daily_wages.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_daily_wage')}}">
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
                                    <label for="user_id" class="control-label col-lg-2">{{__('employees.day_labour')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($dayLabours as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label for="work_date" class="control-label col-lg-2">{{__('employees.work_date')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control date-range-picker" name="work_date"
                                                   type="text"
                                                   id="work_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="wages" class="control-label col-lg-2">{{__('employees.wages')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input name="wages" id="wages" class="form-control">
                                            <div class="input-group-btn">
                                                <select name="op_wages" id="op_wages"
                                                        class="form-control" aria-label="">
                                                    <option value="1">{{__('commons.equal')}}</option>
                                                    <option value="2">{{__('commons.&more')}}</option>
                                                    <option value="3">{{__('commons.&less')}}</option>
                                                    <option value="4">{{__('commons.between')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <label for="paying_status" class="control-label col-lg-2">{{__('employees.paying_status')}}</label>
                                    <div class="col-lg-3">
                                        <select class="form-control" name="paying_status" id="paying_status">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            <option value="Paid">{{__('commons.paid')}}</option>
                                            <option value="Unpaid">{{__('commons.unpaid')}}</option>
                                        </select>
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
                                <th>{{__('employees.labour')}}</th>
                                <th class="text-right">{{__('employees.wages')}}</th>
                                <th>{{__('employees.paying_status')}}</th>
                                <th>{{__('employees.work_date')}}</th>
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
            let workDateRangeObj = $('#work_date');
            let workStartDate = '';
            let workEndDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[4, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'wages', name: 'wages', className: "text-right"},
                    {data: 'paying_status', name: 'paying_status'},
                    {data: 'work_date', name: 'work_date'},
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
                    $('.dataTables_filter').append(`{!! view('commons.button') !!}`);

                    $('#btnExportXLSX').on('click',function () {
                        location.href = '{{ route('daily_wages.exportXLSX') }}' + dataTableUrl;
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
            workDateRangeObj.daterangepicker(dateRangeOptions);
            workDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                workStartDate = picker.startDate.format('YYYY-MM-DD');
                workEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            workDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                workStartDate = '';
                workEndDate = '';
            });

            let getUrlQueries = function() {
                let userId = $('#user_id').val();
                let wages = $('#wages').val();
                let opWages = $('#op_wages').val();
                let payingStatus = $('#paying_status').val();

                return '?workStartDate=' + workStartDate
                    + '&workEndDate=' + workEndDate
                    + '&userId=' + userId
                    + '&wages=' + wages
                    + '&opWages=' + opWages
                    + '&payingStatus=' + payingStatus;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('daily_wages.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
