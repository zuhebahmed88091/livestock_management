@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.create_role')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('roles.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.roles')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')
    {{ $myValue }}
    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('adminPrivilege.create_new_role')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_role')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('roles.store') }}" id="create_role_form"
              name="create_role_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('roles.form', ['role' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('adminPrivilege.add_role')}}</button>
            </div>
        </form>
    </div>

@endsection
