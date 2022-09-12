@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.roles')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('adminPrivilege.roles')}}</a></li>
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

                    @if(count($roles) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                @if (App\Helpers\CommonHelper::isCapable('roles.create'))
                                    <a href="{{ route('roles.create') }}"
                                       class="btn btn-sm btn-success pull-right"
                                       title="{{__('buttonTitle.create_new_role')}}">
                                        <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="panel-body text-center">
                            <h4>{{__('adminPrivilege.no_roles_available')}}</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped w-100">
                                <thead>
                                <tr>
                                    <th>{{__('commons.sl')}}</th>
                                    <th>{{__('commons.name')}}</th>
                                    <th>{{__('adminPrivilege.display_name')}}</th>
                                    <th>{{__('commons.created_at')}}</th>
                                    <th class="text-center mw-100">{{__('commons.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td></td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->created_at }}</td>

                                        <td class="text-center w-120" >

                                            <form method="POST"
                                                  action="{!! route('roles.destroy', $role->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                @if (App\Helpers\CommonHelper::isCapable('roles.module-permissions'))
                                                    <a href="{{ route('roles.module-permissions', $role->id ) }}"
                                                       class="btn btn-xs btn-warning" title="{{__('buttonTitle.assign_permissions')}}">
                                                        <i aria-hidden="true" class="fa fa-key"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('roles.show'))
                                                    <a href="{{ route('roles.show', $role->id ) }}"
                                                       class="btn btn-xs btn-info" title="{{__('buttonTitle.show_role')}}">
                                                        <i aria-hidden="true" class="fa fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('roles.edit'))
                                                    <a href="{{ route('roles.edit', $role->id ) }}"
                                                       class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_role')}}">
                                                        <i aria-hidden="true" class="fa fa-pencil"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('roles.destroy'))
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            title="{{__('buttonTitle.delete_role')}}"
                                                            onclick="return confirm('Delete Role?')">
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
                "order": [[3, "desc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('roles.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif' +

                        '@if (App\Helpers\CommonHelper::isCapable('roles.create'))' +
                        '<a href="{{ route('roles.create') }}" ' +
                        'class="btn btn-sm btn-flat btn-success" ' +
                        'title="{{__('buttonTitle.create_new_role')}}"> ' +
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

            $('#btnExportXLSX').on('click',function () {
                location.href = '{{ route('roles.exportXLSX') }}';
            });
        });
    </script>
@endsection
