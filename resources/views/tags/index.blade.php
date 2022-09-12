@extends('layouts.app')

@section('content-header')
    <h1>{{__('tag.tags')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('tag.tags')}}</a></li>
        <li class="active">{{__('commons.listing')}}</li>
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

                    @if(count($tags) == 0)
                    <div class="row">
                        <div class="col-sm-12">
                            @if (App\Helpers\CommonHelper::isCapable('tags.create'))
                            <a href="{{ route('tags.create') }}"
                               class="btn btn-sm btn-success pull-right"
                               title="{{__('buttonTitle.create_new_tag')}}">
                                <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="panel-body text-center">
                        <h4>{{__('tag.no_tags_available')}}</h4>
                    </div>
                    @else
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>{{__('commons.sl')}}</th>
								<th>{{__('commons.title')}}</th>
								<th>{{__('commons.created_by')}}</th>
								<th class="text-center mw-100">{{__('commons.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tags as $tag)
                            <tr>
                                <td></td>
								<td>{{ $tag->title }}</td>
								<td>{{ optional($tag->creator)->name }}</td>
								<td class="text-center mw-100">

                                    <form method="POST"
                                          action="{!! route('tags.destroy', $tag->id) !!}"
                                          accept-charset="UTF-8">
                                        <input name="_method" value="DELETE" type="hidden">
                                        {{ csrf_field() }}

                                        @if (App\Helpers\CommonHelper::isCapable('tags.show'))
                                        <a href="{{ route('tags.show', $tag->id ) }}"
                                           class="btn btn-xs btn-info" title="Show Tag">
                                            <i aria-hidden="true" class="fa fa-eye"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('tags.edit'))
                                        <a href="{{ route('tags.edit', $tag->id ) }}"
                                           class="btn btn-xs btn-primary" title="Edit Tag">
                                            <i aria-hidden="true" class="fa fa-pencil"></i>
                                        </a>
                                        @endif

                                        @if (App\Helpers\CommonHelper::isCapable('tags.destroy'))
                                        <button type="submit" class="btn btn-xs btn-danger"
                                                title="{{__('buttonTitle.delete_tag')}}"
                                                onclick="return confirm('Delete Tag?')">
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
            let dataTable = $('#dataTable').DataTable({
                "order": [[1, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": -1},
                    {"searchable": false, "orderable": false, "targets": 0}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '@if (App\Helpers\CommonHelper::isCapable('tags.create'))' +
                        '<a href="{{ route('tags.create') }}" ' +
                        'class="btn btn-sm btn-success ml-10" ' +
                        'title="{{__('buttonTitle.create_new_tag')}}"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>' +
                        '@endif'
                    );
                }
            });

            dataTable.on('order.dt search.dt', function () {
                dataTable.column(0, {search: 'applied', order: 'applied'})
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        });
    </script>
@endsection
