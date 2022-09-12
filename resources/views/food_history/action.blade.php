<form method="POST"
      action="{!! route('foodHistory.destroy', $foodHistory->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('foodHistory.show'))
        <a href="{{ route('foodHistory.show', $foodHistory->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_foodHistory')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('foodHistory.edit'))
        <a href="{{ route('foodHistory.edit', $foodHistory->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_foodHistory')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('foodHistory.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_foodHistory')}}"
                onclick="return confirm('Delete Food History?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
