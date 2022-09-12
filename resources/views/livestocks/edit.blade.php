@extends('layouts.app')

@section('content-header')
    <h1>{{__('livestocks.edit_livestock')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('livestocks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('livestocks.livestocks')}}
            </a>
        </li>
        <li class="active">{{__('livestocks.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($liveStock->livestock_name) ? ucfirst($liveStock->livestock_name) : 'Livestock' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('livestocks.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_livestocks')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('livestocks.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_livestock')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('livestocks.update', $liveStock->id) }}"
              id="edit_livestocks_form"
              name="edit_livestocks_form" accept-charset="UTF-8" >
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

                @include ('livestocks.form', ['liveStock' => $liveStock,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection