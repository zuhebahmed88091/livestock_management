@extends('layouts.app')

@section('content-header')
    <h1>{{__('setings.site_settings')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('settings.index') }}">
                <i class="fa fa-dashboard"></i> {{__('setings.settings')}}
            </a>
        </li>
        <li class="active">{{__('commons.update')}}</li>
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

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('setings.all_settings')}}
            </h3>
        </div>

        <form method="POST" action="{{ route('settings.update_batch') }}" accept-charset="UTF-8" class="form-horizontal"
              enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PUT">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @foreach($settings as $setting)
                    <div class="form-group">
                        <label for="{{ $setting->constant }}"
                               class="control-label col-lg-3">{{ $setting->title }}</label>
                        <div class="col-lg-4">
                            @if ($setting->field_type == 'Options')
                                <select class="form-control select2" name="{{ $setting->constant }}"
                                        id="{{ $setting->constant }}">
                                    @foreach($setting->options as $key => $display_name)
                                        <option value="{{ $key }}" {{ ($key == $setting->value) ? 'selected' : '' }}>
                                            {{ $display_name }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif ($setting->field_type == 'File')
                                <div class="input-group">
                                    @if(!empty($setting->value))
                                        <label class="input-group-btn">
                                            <img
                                                src="{{ asset('storage/sites/' . $setting->value) }}"
                                                alt="Logo Image"
                                                class="form-image">
                                        </label>
                                    @endif
                                    <input
                                        value="{{ $setting->value }}"
                                        type="text"
                                        id="{{ $setting->constant }}"
                                        class="form-control" readonly {{ empty($setting->value) ? 'required' : '' }}>
                                    <label class="input-group-btn">
                                        <span class="btn btn-warning">
                                            Browse&hellip; <input class="d-none-imp" type="file" name="{{ $setting->constant }}">
                                        </span>
                                    </label>
                                </div>
                            @elseif ($setting->field_type == 'DateFormat')
                                <select class="form-control select-admin-lte" name="{{ $setting->constant }}"
                                        id="{{ $setting->constant }}">
                                    @foreach($setting->options as $option)
                                        <option value="{{ $option }}"
                                            {{ ($option == $setting->value) ? 'selected' : '' }}>
                                            {{ $option }} ({{ date($option, time()) }})
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input class="form-control" name="{{ strtolower($setting->constant) }}"
                                       id="{{ $setting->constant }}"
                                       value="{{ old('constant', optional($setting)->value) }}" minlength="1"
                                       maxlength="255" required/>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('setings.update_settings')}}</button>
            </div>
        </form>
    </div>

@endsection
