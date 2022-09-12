@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.consume_inventory_stock')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventory_stocks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventory_stocks')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('inventory.current_stock')}} {{ $selectedInventory->currentStockQuantity }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('inventory_stocks.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_inventory_stock')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('inventory_stocks.store') }}" id="create_inventory_stock_form"
              name="create_inventory_stock_form" accept-charset="UTF-8" >
            <input type="hidden" name="stock_type" value="1">
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('inventory_stocks.form_consume', ['inventoryStock' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('inventory.consume_inventory_stock')}}</button>
            </div>
        </form>
    </div>

@endsection

@section('javascript')
    <script>
        $(document).ready(function () {
            "use strict";
            $('.select-admin-lte').select2().on('change', function () {
                location.href = '{{ url('inventory_stocks/consume/inventories') }}' + '/' + $(this).val();
            });
        });
    </script>
@endsection
