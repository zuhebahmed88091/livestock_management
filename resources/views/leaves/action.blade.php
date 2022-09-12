<form method="POST"
      action="{!! route('leaves.destroy', $leave->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('leaves.show'))
        <a href="{{ route('leaves.show', $leave->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_leave')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('leaves.edit'))
        <a href="{{ route('leaves.edit', $leave->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_leave')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('leaves.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_leave')}}"
                onclick="return confirm('Delete Leave?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
