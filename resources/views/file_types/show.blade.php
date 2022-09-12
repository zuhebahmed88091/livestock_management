@extends('layouts.app')

@section('content-header')
    <h1>File Type Details</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('file_types.index') }}">
                <i class="fa fa-dashboard"></i> File Types
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($fileType->name) ? ucfirst($fileType->name) : 'File Type' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                    action="{!! route('file_types.destroy', $fileType->id) !!}"
                    accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}
                    <a href="{{ route('file_types.index') }}" class="btn btn-sm btn-info" title="Show All File Type">
                        <i class="fa fa-th-list" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('file_types.create') }}" class="btn btn-sm btn-success" title="Create New File Type">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </a>

                    <a href="{{ route('file_types.edit', $fileType->id ) }}"
                        class="btn btn-sm btn-primary" title="Edit File Type">
                        <i aria-hidden="true" class="fa fa-pencil"></i>
                    </a>

                    <button type="submit" class="btn btn-sm btn-danger"
                            title="Delete File Type"
                            onclick="return confirm('Delete File Type?')">
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
                        <th width="25%">{{__('commons.name')}}</th>
                        <td width="75%">{{ $fileType->name }}</td>
                    </tr>
                    <tr>
                        <th width="25%">Status</th>
                        <td width="75%">{{ $fileType->status }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.created_at')}}</th>
                        <td width="75%">{{ $fileType->created_at }}</td>
                    </tr>
                    <tr>
                        <th width="25%">{{__('commons.update_at')}}</th>
                        <td width="75%">{{ $fileType->updated_at }}</td>
                    </tr>

                </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
