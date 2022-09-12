@extends('layouts.app')

@section('content-header')
    <h1>Uploaded Files</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Uploaded Files</a></li>
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

                    @if(count($uploadedFiles) == 0)
                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ route('uploaded_files.create') }}"
                                   class="btn btn-sm btn-success pull-right"
                                   title="Create New Uploaded File">
                                    <i aria-hidden="true" class="fa fa-plus"></i> {{__('commons.create')}}
                                </a>
                            </div>
                        </div>

                        <div class="panel-body text-center">
                            <h4>No Uploaded Files Available!</h4>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-bordered table-striped w-100">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Filename</th>
                                    <th>Original Filename</th>
                                    <th>File Type</th>
                                    <th>User</th>
                                    <th class="text-center mw-100">{{__('commons.action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($uploadedFiles as $uploadedFile)
                                    <tr>
                                        <td>{{ $uploadedFile->id }}</td>
                                        <td>{{ $uploadedFile->filename }}</td>
                                        <td>{{ $uploadedFile->original_filename }}</td>
                                        <td>{{ optional($uploadedFile->fileType)->name }}</td>
                                        <td>{{ optional($uploadedFile->user)->name }}</td>

                                        <td class="text-center mw-100">

                                            <form method="POST"
                                                  action="{!! route('uploaded_files.destroy', $uploadedFile->id) !!}"
                                                  accept-charset="UTF-8">
                                                <input name="_method" value="DELETE" type="hidden">
                                                {{ csrf_field() }}

                                                <a href="{{ route('uploaded_files.show', $uploadedFile->id ) }}"
                                                   class="btn btn-xs btn-info" title="Show Uploaded File">
                                                    <i aria-hidden="true" class="fa fa-eye"></i>
                                                </a>

                                                <a href="{{ route('uploaded_files.edit', $uploadedFile->id ) }}"
                                                   class="btn btn-xs btn-primary" title="Edit Uploaded File">
                                                    <i aria-hidden="true" class="fa fa-pencil"></i>
                                                </a>

                                                <button type="submit" class="btn btn-xs btn-danger"
                                                        title="Delete Uploaded File"
                                                        onclick="return confirm('Delete Uploaded File?')">
                                                    <i aria-hidden="true" class="fa fa-trash"></i>
                                                </button>
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
                "columnDefs": [
                    {"orderable": false, "targets": -1}
                ],
                initComplete: function () {
                    $('.dataTables_filter').append(
                        '<a href="{{ route('uploaded_files.create') }}" ' +
                        'class="btn btn-sm btn-success ml-10" ' +
                        'title="Create New File"> ' +
                        '<i aria-hidden="true" class="fa fa-plus"></i> Create' +
                        '</a>'
                    );
                }
            })
        });
    </script>
@endsection
