@extends('layouts.app')

@section('content-header')
    <h1>{{__('salaries.salary_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('salaries.index') }}">
                <i class="fa fa-dashboard"></i> {{__('salaries.salaries')}}
            </a>
        </li>
        <li class="active">{{__('salaries.commons')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Salary' }} {{__('salaries.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('salaries.destroy', $salary->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('salaries.index'))
                        <a href="{{ route('salaries.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_salary')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('salaries.printDetails'))
                        <a href="{{ route('salaries.printDetails', $salary->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('salaries.create'))
                        <a href="{{ route('salaries.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_salary')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('salaries.edit'))
                        <a href="{{ route('salaries.edit', $salary->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_salary')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('salaries.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_salary')}}"
                                onclick="return confirm('Delete Salary?')">
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
                        <th>{{__('employees.employee')}}</th>
                        <td>{{ optional($salary->user)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('salaries.salary_date')}}</th>
                        <td>{{ $salary->salary_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('ledgers.amount')}}</th>
                        <td>{{ $salary->amount }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.comments')}}</th>
                        <td>{{ $salary->comments }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $salary->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $salary->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
