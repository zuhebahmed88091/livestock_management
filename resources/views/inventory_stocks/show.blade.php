@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.inventory_stock_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventory_stocks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventory_stocks')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Inventory Stock' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('inventory_stocks.destroy', $inventoryStock->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('inventory_stocks.index'))
                        <a href="{{ route('inventory_stocks.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_inventory_stock')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_stocks.create'))
                        <a href="{{ route('inventory_stocks.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_inventory_stock')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_stocks.edit'))
                        <a href="{{ route('inventory_stocks.edit', $inventoryStock->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_inventory_stock')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_stocks.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_inventory_stock')}}"
                                onclick="return confirm('Delete Inventory Stock?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>{{__('inventory.inventory')}}</th>
                        <td>{{ optional($inventoryStock->inventory)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('ledgers.amount')}}</th>
                        <td>{{ $inventoryStock->amount }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ optional($inventoryStock->creator)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $inventoryStock->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $inventoryStock->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
