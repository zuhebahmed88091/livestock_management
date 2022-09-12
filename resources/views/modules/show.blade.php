@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.module_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('modules.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.modules')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($module->name) ? ucfirst($module->name) : 'Module' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('modules.destroy', $module->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('modules.index'))
                        <a href="{{ route('modules.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_module')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.printDetails'))
                        <a href="{{ route('modules.printDetails', $module->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.create'))
                        <a href="{{ route('modules.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_module')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.edit'))
                        <a href="{{ route('modules.edit', $module->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_module')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('modules.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_module')}}"
                                onclick="return confirm('Delete Module?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>{{__('commons.name')}}</th>
                        <td>{{ $module->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('adminPrivilege.slug')}}</th>
                        <td>{{ $module->slug }}</td>
                    </tr>
                    <tr>
                        <th>{{__('adminPrivilege.fa_icon')}}</th>
                        <td>{{ $module->fa_icon }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.status')}}</th>
                        <td>{{ $module->status }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.sorting')}}</th>
                        <td>{{ $module->sorting }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $module->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $module->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
