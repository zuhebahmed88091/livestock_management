@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.edit_inventory_stock')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventory_stocks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventory_stocks')}}
            </a>
        </li>
        <li class="active">{{__('commons.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($title) ? ucfirst($title) : 'Inventory Stock' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('inventory_stocks.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_inventory_stock')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('inventory_stocks.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_inventory_stock')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('inventory_stocks.update', $inventoryStock->id) }}"
              id="edit_inventory_stock_form"
              name="edit_inventory_stock_form" accept-charset="UTF-8" >
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

                @include ('inventory_stocks.form', ['inventoryStock' => $inventoryStock,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection