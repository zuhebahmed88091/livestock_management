@extends('layouts.app')

@section('content-header')
    <h1>{{__('sheds.create_c_type')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('sheds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('sheds.sheds')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('sheds.create_nc_type')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('sheds.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_sheds')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('sheds.store') }}" id="create_sheds_form"
              name="create_sheds_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('sheds.form', ['shed' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('sheds.add_c_type')}}</button>
            </div>
        </form>
    </div>

@endsection