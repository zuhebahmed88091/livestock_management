<form method="POST"
      action="{!! route('medicines.destroy', $medicine->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('medicines.show'))
        <a href="{{ route('medicines.show', $medicine->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_medicine')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('medicines.edit'))
        <a href="{{ route('medicines.edit', $medicine->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_medicine')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('medicines.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_medicine')}}"
                onclick="return confirm('Delete Medicine?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
