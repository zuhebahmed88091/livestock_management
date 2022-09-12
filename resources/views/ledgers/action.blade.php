<form method="POST"
      action="{!! route('ledgers.destroy', $ledger->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('ledgers.show'))
        <a href="{{ route('ledgers.show', $ledger->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_ledger')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('ledgers.edit'))
        <a href="{{ route('ledgers.edit', $ledger->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_ledger')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('ledgers.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_ledger')}}"
                onclick="return confirm('Delete Ledger?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
