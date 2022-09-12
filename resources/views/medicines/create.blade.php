@extends('layouts.app')

@section('content-header')
    <h1>{{__('medicine.create_medicine')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('medicines.index') }}">
                <i class="fa fa-dashboard"></i> {{__('medicine.medicines')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('medicine.create_n_medicine')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_medicine')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('medicines.store') }}" id="create_medicine_form"
              name="create_medicine_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('medicines.form', ['medicine' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('medicine.add_medicine')}}</button>
            </div>
        </form>
    </div>

@endsection