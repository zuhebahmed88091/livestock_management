@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.users')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('adminPrivilege.users')}}</a></li>
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

                    @if(count($users) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ route('users.create') }}"
                                   class="btn btn-sm btn-success pull-right"
                                   title="{{__('buttonTitle.create_new_user')}}">
                                    <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                                </a>
                            </div>
                        </div>

                        <div class="panel-body text-center">
                            <h4>{{__('adminPrivilege.no_users_available')}}</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped w-100">
                                <thead>
                                <tr>
                                    <th>{{__('commons.sl')}}</th>
                                    <th class="text-center">{{__('commons.photo')}}</th>
                                    <th>{{__('commons.name')}}</th>
                                    <th>{{__('adminPrivilege.role')}}</th>
                                    <th>{{__('adminPrivilege.email')}}</th>
                                    <th>{{__('adminPrivilege.country')}}</th>
                                    <th>{{__('adminPrivilege.phone')}}</th>
                                    <th>{{__('commons.status')}}</th>
                                    <th>{{__('commons.created_at')}}</th>
                                    <th class="text-center mw-100">{{__('commons.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td></td>
                                        <td class="text-center">
                                            <img
                                                src="{{ asset('storage/profiles/' . optional($user->uploadedFile)->filename) }}"
                                                alt="Profile Image"
                                                class="image-in-list">
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ implode(', ', $user->roles()->pluck('name')->toArray()) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ optional($user->country)->country_name }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->status }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td class="text-center mw-100">

                                            <form method="POST"
                                                  action="{!! route('users.destroy', $user->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                @if (App\Helpers\CommonHelper::isCapable('users.show'))
                                                    <a href="{{ route('users.show', $user->id ) }}"
                                                       class="btn btn-xs btn-info" title="{{__('buttonTitle.show_user')}}">
                                                        <i aria-hidden="true" class="fa fa-eye"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('users.edit'))
                                                    <a href="{{ route('users.edit', $user->id ) }}"
                                                       class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_user')}}">
                                                        <i aria-hidden="true" class="fa fa-pencil"></i>
                                                    </a>
                                                @endif

                                                @if (App\Helpers\CommonHelper::isCapable('users.destroy'))
                                                    <button type="submit" class="btn btn-xs btn-danger"
                                                            title="{{__('buttonTitle.delete_user')}}"
                                                            onclick="return confirm('Delete User?')">
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
                "order": [[8, "desc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0},
                    {"searchable": false, "orderable": false, "targets": 1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('users.exportXLSX'))' +
                        `{!! view('commons.button') !!}` +
                        '@endif' +

                        '@if (App\Helpers\CommonHelper::isCapable('users.create'))' +
                        '<a href="{{ route('users.create') }}" ' +
                        'class="btn btn-sm btn-flat btn-success" ' +
                        'title="{{__('buttonTitle.create_new_user')}}"> ' +
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
                location.href = '{{ route('users.exportXLSX') }}';
            });
        });
    </script>
@endsection
