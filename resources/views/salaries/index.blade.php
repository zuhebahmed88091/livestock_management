@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('salaries.salaries')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('salaries.salaries')}}</a></li>
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
                        <a href="{{ route('salaries.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('salaries.create'))
                            <a href="{{ route('salaries.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_salary')}}">
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
                                    <label for="user_id" class="control-label col-lg-2">{{__('employees.employee')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="user_id" id="user_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($employees as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label for="salary_date" class="control-label col-lg-2">{{__('salaries.salary_date')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input class="form-control date-range-picker" name="salary_date"
                                                   type="text"
                                                   id="salary_date">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="amount" class="control-label col-lg-2">{{__('salaries.salary_amount')}}</label>
                                    <div class="col-lg-4">
                                        <div class="input-group">
                                            <input name="amount" id="amount" class="form-control">
                                            <div class="input-group-btn">
                                                <select name="op_amount" id="op_amount"
                                                        class="form-control" aria-label="">
                                                    <option value="1">{{__('commons.equal')}}</option>
                                                    <option value="2">{{__('commons.&more')}}</option>
                                                    <option value="3">{{__('commons.&less')}}</option>
                                                    <option value="4">{{__('commons.between')}}</option>
                                                </select>
                                            </div>
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
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
                                <th>{{__('employees.employee')}}</th>
                                <th>{{__('salaries.salary_date')}}</th>
                                <th class="text-right">{{__('ledgers.amount')}}</th>
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
            let salaryDateRangeObj = $('#salary_date');
            let salaryStartDate = '';
            let salaryEndDate = '';
            let dataTableUrl = '';

            let dataTable = $('#dataTable').DataTable({
                "order": [[2, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'salary_date', name: 'salary_date'},
                    {data: 'amount', name: 'amount', className: "text-right"},
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
                        location.href = '{{ route('salaries.exportXLSX') }}' + dataTableUrl;
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
            salaryDateRangeObj.daterangepicker(dateRangeOptions);
            salaryDateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                salaryStartDate = picker.startDate.format('YYYY-MM-DD');
                salaryEndDate = picker.endDate.format('YYYY-MM-DD');
                $(this).val(picker.startDate.format('MMMM D, YYYY') + ' - ' + picker.endDate.format('MMMM D, YYYY'));
            });
            salaryDateRangeObj.on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                salaryStartDate = '';
                salaryEndDate = '';
            });

            let getUrlQueries = function() {
                let userId = $('#user_id').val();
                let amount = $('#amount').val();
                let opAmount = $('#op_amount').val();
                return '?salaryStartDate=' + salaryStartDate
                    + '&salaryEndDate=' + salaryEndDate
                    + '&userId=' + userId
                    + '&amount=' + amount
                    + '&opAmount=' + opAmount;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('salaries.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
