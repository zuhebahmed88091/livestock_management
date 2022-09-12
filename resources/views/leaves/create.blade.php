@extends('layouts.app')

@section('content-header')
    <h1>{{__('employees.create_leave')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('leaves.index') }}">
                <i class="fa fa-dashboard"></i> {{__('employees.leaves')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
               {{__('employees.create_new_leave')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_leave')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('leaves.store') }}" id="create_leave_form"
              name="create_leave_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('leaves.form', ['leave' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('employees.add_leave')}}</button>
            </div>
        </form>
    </div>

@endsection