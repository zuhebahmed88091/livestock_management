@extends('layouts.app')

@section('content-header')
    <h1>{{__('livestocks.create_c_type')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('livestocks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('livestocks.livestocks')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('livestocks.create_nc_type')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('livestocks.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_livestocks')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('livestocks.store') }}" id="create_livestocks_form"
              name="create_livestocks_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('livestocks.form', ['liveStock' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('livestocks.add_c_type')}}</button>
            </div>
        </form>
    </div>

@endsection