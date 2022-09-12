@extends('layouts.app')

@section('content-header')
    <h1>{{__('medicine.edit_medicine')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('medicines.index') }}">
                <i class="fa fa-dashboard"></i> {{__('medicine.medicine')}}
            </a>
        </li>
        <li class="active">{{__('commons.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($title) ? ucfirst($title) : 'Medicine' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_medicine')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('medicines.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create-new_medicine')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('medicines.update', $medicine->id) }}"
              id="edit_medicine_form"
              name="edit_medicine_form" accept-charset="UTF-8" >
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

                @include ('medicines.form', ['medicine' => $medicine,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('medicine.update')}}</button>
            </div>
        </form>

    </div>

@endsection