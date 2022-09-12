@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('employees.leaves')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('employees.leaves')}}</a></li>
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
                        <a href="{{ route('leaves.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('leaves.create'))
                            <a href="{{ route('leaves.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_leave')}}">
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
                                    <label for="user_id" class="control-label col-lg-2"> {{__('employees.employee')}}</label>
                                    <div class="col-lg-3">
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($employees as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label for="leave_date" class="control-label col-lg-2">{{__('employees.leave_date')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control date-range-picker" name="leave_date"
                                                   type="text"
                                                   id="leave_date">
                                        </div>
                                    </div>

                                    <div class="col-lg-1">
                                        <button type="button" id="btnSearch" class="btn btn-primary pull-right">{{__('commons.go')}}
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
                                <th>{{__('employees.employee')}}</th>
                                <th>{{__('commons.start_date')}}</th>
                                <th>{{__('commons.end_date')}}</th>
                                <th class="text-right">{{__('commons.days')}}</th>
                                <th class="text-center w-120 mw-100">{{__('commons.action')}}</th>
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
            let leaveDateRangeObj = $('#leave_date');
            let leaveStartDate = '';
            let leaveEndDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[3, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'days', name: 'days', className: "text-right"},
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
                        location.href = '{{ route('leaves.exportXLSX') }}' + dataTableUrl;
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
            leaveDateRangeObj.daterangepicker(dateRangeOptions);
            leaveDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                leaveStartDate = picker.startDate.format('YYYY-MM-DD');
                leaveEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            leaveDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                leaveStartDate = '';
                leaveEndDate = '';
            });

            let getUrlQueries = function() {
                let userId = $('#user_id').val();
                return '?leaveStartDate=' + leaveStartDate
                    + '&leaveEndDate=' + leaveEndDate
                    + '&userId=' + userId;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('leaves.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
