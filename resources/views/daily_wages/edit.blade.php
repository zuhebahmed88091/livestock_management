@extends('layouts.app')

@section('content-header')
    <h1>Edit Daily Wage</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('daily_wages.index') }}">
                <i class="fa fa-dashboard"></i> {{__('employees.daily_wages')}}
            </a>
        </li>
        <li class="active">{{__('commons.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($title) ? ucfirst($title) : 'Daily Wage' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('daily_wages.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_daily_wage')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('daily_wages.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_daily_wage')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('daily_wages.update', $dailyWage->id) }}"
              id="edit_daily_wage_form"
              name="edit_daily_wage_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="box-body">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('daily_wages.form', ['dailyWage' => $dailyWage,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection