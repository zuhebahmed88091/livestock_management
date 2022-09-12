<form method="POST"
      action="{!! route('livestocks.destroy', $liveStock->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('livestocks.show'))
        <a href="{{ route('livestocks.show', $liveStock->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_livestocks')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('livestocks.edit'))
        <a href="{{ route('livestocks.edit', $liveStock->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_livestock')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('livestocks.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_livestock')}}"
                onclick="return confirm('Delete Livestock?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
