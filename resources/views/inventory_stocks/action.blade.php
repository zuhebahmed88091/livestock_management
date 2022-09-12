@if (App\Helpers\CommonHelper::isCapable('inventory_stocks.details'))
    <a href="{{ route('inventory_stocks.details', $inventory->id ) }}"
       class="btn btn-xs btn-info" title="{{__('buttonTitle.show_inventory_stock')}}">
        <i aria-hidden="true" class="fa fa-balance-scale"></i>
    </a>
@endif

@if (App\Helpers\CommonHelper::isCapable('inventory_stocks.create'))
    <a href="{{ route('inventory_stocks.create', $inventory->id ) }}"
       class="btn btn-xs btn-success" title="{{__('buttonTitle.add_inventory_stock')}}">
        <i aria-hidden="true" class="fa fa-plus"></i>
    </a>
@endif

@if (App\Helpers\CommonHelper::isCapable('inventory_stocks.consume'))
    <a href="{{ route('inventory_stocks.consume', $inventory->id ) }}"
       class="btn btn-xs btn-danger" title="{{__('buttonTitle.consume_inventory_stock')}}">
        <i aria-hidden="true" class="fa fa-minus-circle"></i>
    </a>
@endif
