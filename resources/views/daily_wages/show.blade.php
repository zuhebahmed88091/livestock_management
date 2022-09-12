@extends('layouts.app')

@section('content-header')
    <h1>{{__('employees.daily_wage_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('daily_wages.index') }}">
                <i class="fa fa-dashboard"></i> {{__('employees.daily_wages')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Daily Wage' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('daily_wages.destroy', $dailyWage->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('daily_wages.index'))
                        <a href="{{ route('daily_wages.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_daily_wage')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('daily_wages.printDetails'))
                        <a href="{{ route('daily_wages.printDetails', $dailyWage->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('daily_wages.create'))
                        <a href="{{ route('daily_wages.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_daily_wage')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('daily_wages.edit'))
                        <a href="{{ route('daily_wages.edit', $dailyWage->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_daily_wage')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('daily_wages.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_daily_wage')}}"
                                onclick="return confirm('Delete Daily Wage?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>{{__('employees.labour')}}</th>
                        <td>{{ optional($dailyWage->user)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('employees.wages')}}</th>
                        <td>{{ $dailyWage->wages }}</td>
                    </tr>
                    <tr>
                        <th>{{__('employees.paying_status')}}</th>
                        <td>{{ $dailyWage->paying_status }}</td>
                    </tr>
                    <tr>
                        <th>{{__('employees.work_date')}}</th>
                        <td>{{ $dailyWage->work_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.comments')}}</th>
                        <td>{{ $dailyWage->comments }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $dailyWage->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $dailyWage->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
