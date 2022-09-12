<form method="POST"
      action="{!! route('salaries.destroy', $salary->id) !!}"
      accept-charset="UTF-8">
    <input name="_method" value="DELETE" type="hidden">
    {{ csrf_field() }}

    @if (App\Helpers\CommonHelper::isCapable('salaries.show'))
        <a href="{{ route('salaries.show', $salary->id ) }}"
           class="btn btn-xs btn-info" title="{{__('buttonTitle.show_salary')}}">
            <i aria-hidden="true" class="fa fa-eye"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('salaries.edit'))
        <a href="{{ route('salaries.edit', $salary->id ) }}"
           class="btn btn-xs btn-primary" title="{{__('buttonTitle.edit_salary')}}">
            <i aria-hidden="true" class="fa fa-pencil"></i>
        </a>
    @endif

    @if (App\Helpers\CommonHelper::isCapable('salaries.destroy'))
        <button type="submit" class="btn btn-xs btn-danger"
                title="{{__('buttonTitle.delete_salary')}}"
                onclick="return confirm('Delete Salary?')">
            <i aria-hidden="true" class="fa fa-trash"></i>
        </button>
    @endif
</form>
