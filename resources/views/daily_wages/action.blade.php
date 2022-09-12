<form method="POST"
      action="{!! route('daily_wages.destroy', $dailyWage->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('daily_wages.show'))
        <a href="{{ route('daily_wages.show', $dailyWage->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_daily_wage')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('daily_wages.edit'))
        <a href="{{ route('daily_wages.edit', $dailyWage->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_daily_wage')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('daily_wages.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_daily_wage')}}"
                onclick="return confirm('Delete Daily Wage?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
