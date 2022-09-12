@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.create_user')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('users.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.users')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('adminPrivilege.create_new_user')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('users.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_user')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('users.store') }}" id="create_user_form"
              name="create_user_form" accept-charset="UTF-8" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('users.form', ['user' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('adminPrivilege.add_user')}}</button>
            </div>
        </form>
    </div>

@endsection
