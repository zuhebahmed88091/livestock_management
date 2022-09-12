<form method="POST"
      action="{!! route('permissions.destroy', $permission->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('permissions.show'))
        <a href="{{ route('permissions.show', $permission->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_permission')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('permissions.edit'))
        <a href="{{ route('permissions.edit', $permission->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_permission')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('permissions.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_permission')}}"
                onclick="return confirm('Delete Permission?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
