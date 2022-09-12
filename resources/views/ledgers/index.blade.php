@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('ledgers.ledgers')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('ledgers.ledgers')}}</a></li>
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
                        <a href="{{ route('ledgers.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('ledgers.create'))
                            <a href="{{ route('ledgers.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_ledger')}}">
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
                                    <label for="type" class="control-label col-lg-2">{{__('commons.type')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="type" id="type">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            <option value="Income">{{__('ledgers.income')}}</option>
                                            <option value="Expense">{{__('ledgers.expense')}}</option>
                                        </select>
                                    </div>

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
                                    <label for="amount" class="control-label col-lg-2">{{__('ledgers.amount')}}</label>
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

                                    <label for="tag_id" class="control-label col-lg-2">{{__('commons.tag')}}</label>
                                    <div class="col-lg-3">
                                        <select class="form-control select-admin-lte" name="tag_id" id="tag_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($tags as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
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
                                <th>{{__('commons.type')}}</th>
                                <th>{{__('commons.date')}}</th>
                                <th class="text-right">{{__('ledgers.amount')}}</th>
                                <th>{{__('commons.tag')}}</th>
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
                "order": [[2, "desc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'type', name: 'type'},
                    {data: 'date', name: 'date'},
                    {data: 'amount', name: 'amount', className: "text-right"},
                    {data: 'tag', name: 'tag'},
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
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('ledgers.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif' +

                        '@if (App\Helpers\CommonHelper::isCapable('ledgers.balanceSheet'))' +
                        '<a href="{{ route('ledgers.balanceSheet') }}" ' +
                        'class="btn btn-sm btn-flat btn-warning" ' +
                        'title="Show balance"> ' +
                        '<i aria-hidden="true" class="fa fa-balance-scale"></i> Balance Sheet' +
                        '</a>' +
                        '@endif'
                    );

                    $('#btnExportXLSX').on('click',function () {
                        location.href = '{{ route('ledgers.exportXLSX') }}' + dataTableUrl;
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

            let getUrlQueries = function () {
                let type = $('#type').val();
                let amount = $('#amount').val();
                let opAmount = $('#op_amount').val();
                let tagId = $('#tag_id').val();
                return '?startDate=' + startDate
                    + '&endDate=' + endDate
                    + '&type=' + type
                    + '&amount=' + amount
                    + '&opAmount=' + opAmount
                    + '&tagId=' + tagId;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('ledgers.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });

    </script>
@endsection
