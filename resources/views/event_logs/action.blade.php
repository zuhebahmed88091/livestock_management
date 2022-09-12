<form method="POST"
      action="{!! route('event_logs.destroy', $eventLog->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('event_logs.show'))
        <a href="{{ route('event_logs.show', $eventLog->id ) }}"
           class="btn btn-xs btn-info" title="Show Event Log">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('event_logs.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_event_log')}}"
                onclick="return confirm('Delete Event Log?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
