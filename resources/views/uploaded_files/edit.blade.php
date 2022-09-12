@extends('layouts.app')

@section('content-header')
    <h1>Edit Uploaded File</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('uploaded_files.index') }}">
                <i class="fa fa-dashboard"></i> Uploaded Files
            </a>
        </li>
        <li class="active">{{__('commons.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($title) ? ucfirst($title) : 'Uploaded File' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('uploaded_files.index') }}" class="btn btn-sm btn-info"
                   title="Show All Uploaded File">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('uploaded_files.create') }}" class="btn btn-sm btn-success"
                   title="Create New Uploaded File">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('uploaded_files.uploaded_file.update', $uploadedFile->id) }}"
              id="edit_uploaded_file_form"
              name="edit_uploaded_file_form" accept-charset="UTF-8" >
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="box-body">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('uploaded_files.form', ['uploadedFile' => $uploadedFile,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection
