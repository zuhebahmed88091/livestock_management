@extends('layouts.app')

@section('content-header')
    <h1>{{__('sheds.edit_shed')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('sheds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('sheds.sheds')}}
            </a>
        </li>
        <li class="active">{{__('sheds.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($shed->liveStock->livestock_name) ? ucfirst($shed->liveStock->livestock_name) : 'Shed' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('sheds.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_sheds')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('sheds.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_shed')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('sheds.update', $shed->id) }}"
              id="edit_sheds_form"
              name="edit_sheds_form" accept-charset="UTF-8" >
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

                @include ('sheds.form', ['shed' => $shed,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection