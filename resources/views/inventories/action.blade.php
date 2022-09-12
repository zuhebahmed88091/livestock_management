<form method="POST"
      action="{!! route('inventories.destroy', $inventory->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('inventories.show'))
        <a href="{{ route('inventories.show', $inventory->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_inventory')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('inventories.edit'))
        <a href="{{ route('inventories.edit', $inventory->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_inventory')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('inventories.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_inventory')}}"
                onclick="return confirm('Delete Inventory?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
