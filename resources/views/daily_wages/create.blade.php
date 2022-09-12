@extends('layouts.app')

@section('content-header')
    <h1>{{__('employees.create_daily_wage')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('daily_wages.index') }}">
                <i class="fa fa-dashboard"></i> {{__('employees.daily_wages')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('employees.create_new_daily_wage')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('daily_wages.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_daily_wage')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('daily_wages.store') }}" id="create_daily_wage_form"
              name="create_daily_wage_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('daily_wages.form', ['dailyWage' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('employees.add_daily_Wage')}}</button>
            </div>
        </form>
    </div>

@endsection