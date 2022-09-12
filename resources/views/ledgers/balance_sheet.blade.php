@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('ledgers.balance_sheet')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('ledgers.ledgers')}}</a></li>
        <li class="active">{{__('ledgers.balance_sheet')}}</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header with-border">
                    <div class="row">

                        <div class="col-md-6">
                            <a href="{{ route('ledgers.index') }}" title="Go back" class="btn btn-sm btn-primary">
                                <i aria-hidden="true" class="fa fa-arrow-left"></i> {{__('commons.go_back')}}
                            </a>

                            @if (App\Helpers\CommonHelper::isCapable('ledgers.printBalanceSheet'))
                                <button type="button" class="btn btn-sm btn-warning btn-print"
                                        title="{{__('buttonTitle.print_balance_sheet')}}">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>

                        <div class="col-md-6">

                            <form id="formSearch" method="POST" class="form-inline pull-right">
                                <div class="form-group">
                                    <label for="date" class="control-label">{{__('commons.date')}}</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control date-range-picker" name="date"
                                               type="text"
                                               id="date">
                                    </div>
                                </div>
                                <button type="button" id="btnSearch" class="btn btn-primary">
                                    {{__('commons.go')}}
                                </button>
                            </form>

                        </div>

                    </div>
                </div>

                <div class="box-body">
                    <div class="table-responsive" id="tableBalanceSheet"></div>
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
            let start = moment().subtract(29, 'days');
            let end = moment();
            let startDate = start.format('YYYY-MM-DD');
            let endDate = end.format('YYYY-MM-DD');
            let dateRangeObj = $('#date');

            let dateRangeOptions = {
                opens: 'left',
                showDropdowns: false,
                linkedCalendars: false,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: dateFormat
                },
                startDate: start,
                endDate: end,
                dateLimit: {
                    months: 1
                }
            };

            // daterangepicker for purchase date
            dateRangeOptions.opens = 'left';
            dateRangeObj.daterangepicker(dateRangeOptions);
            dateRangeObj.on('apply.daterangepicker', function (ev, picker) {
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
            });

            let ajaxRequest = function () {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('ledgers.balanceSheet') }}',
                    data: {
                        'startDate': startDate,
                        'endDate': endDate
                    },
                    success: function (htmlData) {
                        $('#tableBalanceSheet').html(htmlData);
                    },
                    error: function (jsonObj) {
                        if (jsonObj.status === 422) {
                            alertify.error(jsonObj.responseJSON.message);
                        }
                    }
                });
            };

            ajaxRequest();

            $('.btn-print').on('click',function () {
                location.href = '{{ route('ledgers.printBalanceSheet') }}'
                    + '?startDate=' + startDate + '&endDate=' + endDate;
            });

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });
    </script>
@endsection


