@extends('layouts.app')

@section('content-header')
    <h1>{{__('setings.setting_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('settings.index') }}">
                <i class="fa fa-dashboard"></i> {{__('setings.settings')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($setting->title) ? ucfirst($setting->title) : 'Setting' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('settings.destroy', $setting->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('settings.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_setting')}}">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('settings.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_setting')}}">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('settings.edit', $setting->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Setting">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Setting"
                            onclick="return confirm('Delete Setting?')">
                        <i aria-hidden="true" class="fa fa-trash"></i>
                    </button>

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th width="25%">{{__('commons.title')}}</th>
                        <td width="75%">{{ $setting->title }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('setings.constant')}}</th>
                        <td width="75%">{{ $setting->constant }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('setings.value')}}</th>
                        <td width="75%">{{ $setting->value }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('setings.field_type')}}</th>
                        <td width="75%">{{ $setting->field_type }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('setings.options')}}</th>
                        <td width="75%">{{ $setting->options }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.status')}}</th>
                        <td width="75%">{{ $setting->status }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection