<form method="POST"
      action="{!! route('sheds.destroy', $shed->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('sheds.show'))
        <a href="{{ route('sheds.show', $shed->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_shed')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('sheds.edit'))
        <a href="{{ route('sheds.edit', $shed->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_shed')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('sheds.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_shed')}}"
                onclick="return confirm('Delete Shed?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
