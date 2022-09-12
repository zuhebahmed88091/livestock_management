@extends('layouts.app')

@section('content-header')
    <h1>{{__('countries.create_country')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('countries.index') }}">
                <i class="fa fa-dashboard"></i> {{__('countries.countries')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('countries.create_new_country')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('countries.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_country')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('countries.store') }}" id="create_country_form"
              name="create_country_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('countries.form', ['country' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('countries.add_country')}}</button>
            </div>
        </form>
    </div>

@endsection