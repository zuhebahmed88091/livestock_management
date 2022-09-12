@extends('layouts.app')

@section('content-header')
    <h1>{{__('employees.leave_details')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('employees.leaves')}}</a></li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

                    @if(count($leaves) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('leaves.create'))
                            <a href="{{ route('leaves.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="{{__('buttonTitle.create_new_leave')}}">
                                <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>{{__('employees.no_leaves_available')}}</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
								<th>{{__('commons.id')}}</th>
								<th>{{__('employees.employee')}}</th>
								<th>{{__('employees.leave_duration')}}</th>
								<th class="text-right">{{__('commons.days')}}</th>
								<th class="text-center mxw-70">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($leaves as $leave)
                            <tr>
								<td>{{ $leave->id }}</td>
								<td>{{ optional($leave->user)->name }}</td>
								<td>{{ $leave->start_date }} to {{ $leave->end_date }}</td>
								<td class="text-right">
                                    {{ App\Helpers\CommonHelper::getDays($leave->start_date, $leave->end_date) }} days
                                </td>
								<td class="text-center mw-100">

                                    <form method="POST"
                                          action="{!! route('leaves.destroy', $leave->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

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

                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @endif

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

<!-- page script -->
@section('javascript')
    <script>
        $(function () {
            "use strict";
            $('#dataTable').DataTable({
                "order": [[ 0, "desc" ]],
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('leaves.index'))' +
                        '<a href="{{ route('leaves.index') }}" ' +
                        'class="btn btn-sm btn-primary ml-10" ' +
                        'title="Create New User"> ' +
                        '<i aria-hidden="true" class="fa fa-reply"></i> Go Back' +
                        '</a>' +
                        '@endif'
                    );
                }
            })
        });
    </script>
@endsection
