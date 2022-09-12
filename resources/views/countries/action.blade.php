<form method="POST"
      action="{!! route('countries.destroy', $country->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('countries.show'))
        <a href="{{ route('countries.show', $country->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_country')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('countries.edit'))
        <a href="{{ route('countries.edit', $country->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_country')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('countries.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_country')}}"
                onclick="return confirm('Delete Country?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
