@extends('layouts.app')

@section('content-header')
    <h1>{{__('livestocks_type.create_livestock_type')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('livestocks_types.index') }}">
                <i class="fa fa-dashboard"></i> {{__('livestocks_type.livestock_type')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
               {{__('livestocks_type.create_new_livestock_type')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('livestocks_types.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_livestock_type')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('livestocks_types.store') }}" id="create_livestock_type_form"
              name="create_livestock_type_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('livestocks_type.form', ['livestocksType' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('livestocks_type.add_livestock_type')}}</button>
            </div>
        </form>
    </div>

@endsection