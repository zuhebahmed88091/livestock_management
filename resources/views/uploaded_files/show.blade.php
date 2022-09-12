@extends('layouts.app')

@section('content-header')
    <h1>Uploaded File Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('uploaded_files.index') }}">
                <i class="fa fa-dashboard"></i> Uploaded Files
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Uploaded File' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('uploaded_files.destroy', $uploadedFile->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('uploaded_files.index') }}" class="btn btn-sm btn-info" title="Show All Uploaded File">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('uploaded_files.create') }}" class="btn btn-sm btn-success" title="Create New Uploaded File">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('uploaded_files.edit', $uploadedFile->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit Uploaded File">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete Uploaded File"
                            onclick="return confirm('Delete Uploaded File?')">
                        <i aria-hidden="true" class="fa fa-trash"></i>
                    </button>

                </form>

            </div>

        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th width="25%">Filename</th>
                        <td width="75%">{{ $uploadedFile->filename }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Original Filename</th>
                        <td width="75%">{{ $uploadedFile->original_filename }}</td>
                    </tr>
                    <tr>
                        <th width="25%">File Type</th>
                        <td width="75%">{{ optional($uploadedFile->fileType)->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">User</th>
                        <td width="75%">{{ optional($uploadedFile->user)->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.created_at')}}</th>
                        <td width="75%">{{ $uploadedFile->created_at }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.update_at')}}</th>
                        <td width="75%">{{ $uploadedFile->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
