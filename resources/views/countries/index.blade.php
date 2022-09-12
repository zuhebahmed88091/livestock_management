@extends('layouts.app')

@section('content-header')
    <h1>{{__('countries.countries')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('countries.countries')}}</a></li>
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
                        <a href="{{ route('countries.index') }}" class="btn btn-info btn-sm">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('countries.create'))
                            <a href="{{ route('countries.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_country')}}">
                                <i class="fa fa-plus"></i> {{__('commons.create')}}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="box-body">

                    <form id="formSearch" method="POST" class="form-horizontal">
                        <div class="form-group">
                            <label for="country_name" class="control-label col-lg-2">{{__('countries.country_name')}}</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="country_name" id="country_name">
                            </div>

                            <label for="country_code" class="control-label col-lg-2">{{__('countries.country_code')}}</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="country_code" id="country_code">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="currency_code" class="control-label col-lg-2">{{__('countries.currency_code')}}</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="currency_code" id="currency_code">
                            </div>

                            <label for="capital" class="control-label col-lg-2">{{__('countries.capital')}}</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="capital" id="capital">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="continent_name" class="control-label col-lg-2">{{__('countries.continent')}}</label>
                            <div class="col-lg-4">
                                <input class="form-control" name="continent_name" id="continent_name">
                            </div>

                            <label for="status" class="control-label col-lg-2">{{__('commons.status')}}</label>
                            <div class="col-lg-3">
                                <select class="form-control" name="status" id="status">
                                    <option value="">------ {{__('commons.all')}} ------</option>
                                    <option value="Active">{{__('commons.active')}}</option>
                                    <option value="Inactive">{{__('commons.inactive')}}</option>
                                </select>
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

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    <div class="table-responsive hide-quick-search">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
                                <th>{{__('countries.country_name')}}</th>
                                <th>{{__('countries.country_code')}}</th>
                                <th>{{__('countries.currency_code')}}</th>
                                <th>{{__('countries.capital')}}</th>
                                <th>{{__('countries.continent_name')}}</th>
                                <th>{{__('countries.continent_code')}}</th>
                                <th>{{__('commons.status')}}</th>
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
            let dataTableUrl = '';
            let dataTable = $('#dataTable').DataTable({
                "order": [[1, "asc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'country_name', name: 'country_name'},
                    {data: 'country_code', name: 'country_code'},
                    {data: 'currency_code', name: 'currency_code'},
                    {data: 'capital', name: 'capital'},
                    {data: 'continent_name', name: 'continent_name'},
                    {data: 'continent_code', name: 'continent_code'},
                    {data: 'status', name: 'status'},
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
                        location.href = '{{ route('countries.exportXLSX') }}' + dataTableUrl;
                    });
                }
            });

            let getUrlQueries = function() {
                let countryName = $('#country_name').val();
                let countryCode = $('#country_code').val();
                let currencyCode = $('#currency_code').val();
                let capital = $('#capital').val();
                let continentName = $('#continent_name').val();
                let status = $('#status').val();
                return '?countryName=' + countryName
                    + '&countryCode=' + countryCode
                    + '&currencyCode=' + currencyCode
                    + '&capital=' + capital
                    + '&continentName=' + continentName
                    + '&status=' + status;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('countries.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });

        });
    </script>
@endsection
