@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('livestocks_type.livestock_type')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('livestocks_type.livestock_type')}}</a></li>
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

                    @if(count($liveStocksTypes) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('livestocks_types.create'))
                            <a href="{{ route('livestocks_types.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="{{__('buttonTitle.create_new_livestock_type')}}">
                                <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>{{__('inventory.no_livestock_types_available')}}</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
								<th> {{__('commons.title')}}</th>
								<th>{{__('commons.status')}}</th>
								<th class="text-center mw-100">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($liveStocksTypes as $liveStocksType)
                            <tr>
								<td></td>
								<td>{{ $liveStocksType->title }}</td> 
								<td>{{ $liveStocksType->status }}</td>
								<td class="text-center mw-100">

                                    <form method="POST"
                                          action="{!! route('livestocks_types.destroy', $liveStocksType->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('livestocks_types.show'))
                                        <a href="{{ route('livestocks_types.show', $liveStocksType->id ) }}"
                                           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_livestock_type')}}">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('livestocks_types.edit'))
                                        <a href="{{ route('livestocks_types.edit', $liveStocksType->id ) }}"
                                           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_livestock_type')}}">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('livestocks_types.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="{{__('buttonTitle.delete_livestock_type')}}"
                                                onclick="return confirm('Delete Livestock Type?')">
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

@endsection


@section('javascript')
    <script>
        $(function () {
            "use strict";
            let dataTable = $('#dataTable').DataTable({
                "order": [[0, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('livestocks_types.create'))' +
                        '<a href="{{ route('livestocks_types.create') }}" ' +
                        'class="btn btn-sm btn-success ml-10"' +
                        'title="{{__('buttonTitle.create_new_livestock_type')}}"> ' +
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
