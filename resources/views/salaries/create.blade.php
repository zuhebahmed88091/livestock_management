@extends('layouts.app')

@section('content-header')
    <h1>{{__('salaries.create_salary')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('salaries.index') }}">
                <i class="fa fa-dashboard"></i> {{__('salaries.salaries')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('salaries.create_new_salary')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('salaries.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_salary')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('salaries.store') }}" id="create_salary_form"
              name="create_salary_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('salaries.form', ['salary' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('salaries.add_salary')}}</button>
            </div>
        </form>
    </div>

@endsection