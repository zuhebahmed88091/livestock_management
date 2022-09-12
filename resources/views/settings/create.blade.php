@extends('layouts.app')

@section('content-header')
    <h1>{{__('setings.create_setting')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('settings.index') }}">
                <i class="fa fa-dashboard"></i> {{__('setings.settings')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('setings.create_new_setting')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('settings.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_setting')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('settings.store') }}" id="create_setting_form"
              name="create_setting_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('settings.form', ['setting' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('setings.add_setting')}}</button>
            </div>
        </form>
    </div>

@endsection