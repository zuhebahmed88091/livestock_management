@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.inventories')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('inventory.inventories')}}</a></li>
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
                        <a href="{{ route('inventories.index') }}" class="btn btn-info btn-sm" title="Collapse">
                            <i class="fa fa-refresh"></i> {{__('commons.reset')}}
                        </a>
                        @if (App\Helpers\CommonHelper::isCapable('inventories.create'))
                            <a href="{{ route('inventories.create') }}"
                               class="btn btn-sm btn-success"
                               title="{{__('buttonTitle.create_new_inventory')}}">
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
                                    <label for="name" class="control-label col-lg-2">{{__('commons.name')}}</label>
                                    <div class="col-lg-4">
                                        <input class="form-control" name="name"
                                               type="text"
                                               id="name">
                                    </div>

                                    <label for="inventory_type_id" class="control-label col-lg-2">{{__('inventory.inventory_type')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="inventory_type_id" id="inventory_type_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($inventoryTypes as $key => $title)
                                                <option value="{{ $key }}">
                                                    {{ $title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="inventory_unit_id" class="control-label col-lg-2">{{__('inventory.inventory_unit')}}</label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="inventory_unit_id" id="inventory_unit_id">
                                            <option value="">------ {{__('commons.all')}} ------</option>
                                            @foreach ($inventoryUnits as $key => $title)
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
                                <th class="text-center">{{__('commons.image')}}</th>
                                <th>{{__('commons.name')}}</th>
                                <th>{{__('inventory.inventory_type')}}</th>
                                <th>{{__('inventory.inventory_unit')}}</th>
                                <th>{{__('inventory.source')}}</th>
                                <th>{{__('inventory.warranty')}}</th>
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
                "order": [[2, "asc"]],
                processing: false,
                serverSide: true,
                ajax: function (data, callback, settings) {
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'image', name: 'image', className: "text-center"},
                    {data: 'name', name: 'name'},
                    {data: 'inventory_type', name: 'inventory_type'},
                    {data: 'inventory_unit', name: 'inventory_unit'},
                    {data: 'source', name: 'source'},
                    {data: 'warranty', name: 'warranty'},
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
                    {"orderable": false, "targets": 0},
                    {"orderable": false, "targets": 1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(`{!! view('commons.button') !!}`);

                    $('#btnExportXLSX').on('click',function () {
                        location.href = '{{ route('inventories.exportXLSX') }}' + dataTableUrl;
                    });
                }
            });

            let getUrlQueries = function() {
                let name = $('#name').val();
                let inventoryTypeId = $('#inventory_type_id').val();
                let inventoryUnitId = $('#inventory_unit_id').val();
                return '?name=' + name
                    + '&inventoryTypeId=' + inventoryTypeId
                    + '&inventoryUnitId=' + inventoryUnitId;
            };

            let ajaxRequest = function () {
                dataTableUrl = getUrlQueries();
                dataTable.ajax.url('{{ route('inventories.index') }}' + dataTableUrl);
                dataTable.draw();
            };

            ajaxRequest();

            $('#btnSearch').on('click',function () {
                ajaxRequest();
            });
        });
    </script>
@endsection
