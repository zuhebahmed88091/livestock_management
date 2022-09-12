@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.inventory_units')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('inventory.inventory_units')}}</a></li>
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
            <div class="box">
                <div class="box-body">

                    @if(count($inventoryUnits) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('inventory_units.create'))
                            <a href="{{ route('inventory_units.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="{{__('buttonTitle.create_new_inventory_unit')}}">
                                <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>{{__('inventory.no_inventory_units_available')}}</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
								<th>{{__('commons.title')}}</th>
								<th>{{__('commons.status')}}</th>
								<th class="text-center mw-100">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($inventoryUnits as $inventoryUnit)
                            <tr>
								<td></td>
								<td>{{ $inventoryUnit->title }}</td>
								<td>{{ $inventoryUnit->status }}</td>
								<td class="text-center mw-100">

                                    <form method="POST"
                                          action="{!! route('inventory_units.destroy', $inventoryUnit->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('inventory_units.show'))
                                        <a href="{{ route('inventory_units.show', $inventoryUnit->id ) }}"
                                           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_inventory_unit')}}">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('inventory_units.edit'))
                                        <a href="{{ route('inventory_units.edit', $inventoryUnit->id ) }}"
                                           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_inventory_unit')}}">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('inventory_units.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="{{__('buttonTitle.delete_inventory_unit')}}"
                                                onclick="return confirm('Delete Inventory Unit?')">
                                            <i aria-hidden="true" class="fa fa-trash"></i>
                                        </button>
                                        @endif
                                    </form>

                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @endif

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
    <script>
        $(function () {
            "use strict";
            let dataTable = $('#dataTable').DataTable({
                "order": [[1, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('inventory_units.create'))' +
                        '<a href="{{ route('inventory_units.create') }}" ' +
                        'class="btn btn-sm btn-success ml-10" ' +
                        'title="{{__('buttonTitle.create_new_inventory_unit')}}"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>' +
                        '@endif'
                    );
                }
            });

            dataTable.on('order.dt search.dt', function () {
                dataTable.column(0, {search: 'applied', order: 'applied'})
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        });
    </script>
@endsection
