@extends('layouts.app')

@section('content-header')
    <h1>{{__('food_history.edit_foodHistory')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('foodHistory.index') }}">
                <i class="fa fa-dashboard"></i> {{__('food_history.food_history')}}
            </a>
        </li>
        <li class="active">{{__('foodHistory.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($foodHistory->inventory->title) ? ucfirst($foodHistory->inventory->title) : 'Food History' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('foodHistory.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_foodHistory')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('foodHistory.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_foodHistory')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('foodHistory.update', $foodHistory->id) }}"
              id="edit_foodHistory_form"
              name="edit_foodHistory_form" accept-charset="UTF-8" >
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

                @include ('food_history.form', ['foodHistory' => $foodHistory,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection