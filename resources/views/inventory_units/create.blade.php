@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.create_inventory_unit')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventory_units.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventory_units')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('inventory.create_inventory_unit')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('inventory_units.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_inventory_unit')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory_units.store') }}" id="create_inventory_unit_form"
              name="create_inventory_unit_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('inventory_units.form', ['inventoryUnit' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('inventory.add_inventoryUnit')}}</button>
            </div>
        </form>
    </div>

@endsection