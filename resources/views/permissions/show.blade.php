@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.permission_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('permissions.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.permissions')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($permission->name) ? ucfirst($permission->name) : 'Permission' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('permissions.destroy', $permission->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('permissions.index'))
                        <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_permission')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('permissions.printDetails'))
                        <a href="{{ route('permissions.printDetails', $permission->id) }}"
                           class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('permissions.create'))
                        <a href="{{ route('permissions.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_permission')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('permissions.edit'))
                        <a href="{{ route('permissions.edit', $permission->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_permission')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('permissions.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_permission')}}"
                                onclick="return confirm('Delete Permission?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th width="25%">{{__('adminPrivilege.module')}}</th>
                        <td width="75%">{{ optional($permission->module)->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.name')}}</th>
                        <td width="75%">{{ $permission->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('adminPrivilege.display_name')}}</th>
                        <td width="75%">{{ $permission->display_name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.description')}}</th>
                        <td width="75%">{{ $permission->description }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.created_at')}}</th>
                        <td width="75%">{{ $permission->created_at }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.update_at')}}</th>
                        <td width="75%">{{ $permission->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
