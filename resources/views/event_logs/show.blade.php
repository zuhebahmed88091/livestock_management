@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.event_log_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('event_logs.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.event_logs')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Event Log' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('event_logs.destroy', $eventLog->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('event_logs.index'))
                        <a href="{{ route('event_logs.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_event_log')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('event_logs.printDetails'))
                        <a href="{{ route('event_logs.printDetails', $eventLog->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('event_logs.create'))
                        <a href="{{ route('event_logs.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_event_log')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('event_logs.edit'))
                        <a href="{{ route('event_logs.edit', $eventLog->id ) }}"
                           class="btn btn-sm btn-primary" title="Edit Event Log">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('event_logs.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_event_log')}}"
                                onclick="return confirm('Delete Event Log?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th width="25%">{{__('adminPrivilege.user')}}</th>
                        <td width="75%">{{ optional($eventLog->user)->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('adminPrivilege.end_point')}}</th>
                        <td width="75%">{{ $eventLog->end_point }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('adminPrivilege.changes')}}</th>
                        <td width="75%">{!! $eventLog->changes !!}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.created_at')}}</th>
                        <td width="75%">{{ $eventLog->created_at }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.update_at')}}</th>
                        <td width="75%">{{ $eventLog->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
