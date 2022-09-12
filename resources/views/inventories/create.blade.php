@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.create_inventory')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventories.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventories')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('inventory.create_new_inventory')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('inventories.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_inventory')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('inventories.store') }}" id="create_inventory_form"
              name="create_inventory_form" accept-charset="UTF-8"  enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('inventories.form', ['inventory' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('inventory.add_inventory')}}</button>
            </div>
        </form>
    </div>

@endsection