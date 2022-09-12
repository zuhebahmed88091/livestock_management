@extends('layouts.app')

@section('content-header')
    <h1>{{__('food_history.create_c_type')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('foodHistory.index') }}">
                <i class="fa fa-dashboard"></i> {{__('food_history.food_history')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('food_history.create_nc_type')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('foodHistory.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_foodHistory')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('foodHistory.store') }}" id="create_food_history_form"
              name="create_food_history_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('food_history.form', ['foodHistory' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('food_history.add_c_type')}}</button>
            </div>
        </form>
    </div>

@endsection