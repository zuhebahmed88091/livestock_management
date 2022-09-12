@extends('layouts.app')

@section('content-header')
    <h1>{{__('employees.leave_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('leaves.index') }}">
                <i class="fa fa-dashboard"></i> {{__('employees.leaves')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Leave' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('leaves.destroy', $leave->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('leaves.index'))
                        <a href="{{ route('leaves.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_leave')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('leaves.printDetails'))
                        <a href="{{ route('leaves.printDetails', $leave->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('leaves.create'))
                        <a href="{{ route('leaves.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_leave')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('leaves.edit'))
                        <a href="{{ route('leaves.edit', $leave->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_leave')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('leaves.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_leave')}}"
                                onclick="return confirm('Delete Leave?')">
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
                        <td>{{ optional($leave->user)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.start_date')}}</th>
                        <td>{{ $leave->start_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.end_date')}}</th>
                        <td>{{ $leave->end_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.comments')}}</th>
                        <td>{{ $leave->comments }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $leave->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $leave->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
