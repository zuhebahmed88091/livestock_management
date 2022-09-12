@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.role_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('roles.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.roles')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($role->name) ? ucfirst($role->name) : 'Role' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('roles.destroy', $role->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('roles.index'))
                        <a href="{{ route('roles.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_role')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('roles.printDetails'))
                        <a href="{{ route('roles.printDetails', $role->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('roles.create'))
                        <a href="{{ route('roles.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_role')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('roles.edit'))
                        <a href="{{ route('roles.edit', $role->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_role')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('roles.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_role')}}"
                                onclick="return confirm('Delete Role?')">
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
                        <th width="25%">{{__('commons.name')}}</th>
                        <td width="75%">{{ $role->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('adminPrivilege.display_name')}}</th>
                        <td width="75%">{{ $role->display_name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.description')}}</th>
                        <td width="75%">{{ $role->description }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.created_at')}}</th>
                        <td width="75%">{{ $role->created_at }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.update_at')}}</th>
                        <td width="75%">{{ $role->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
