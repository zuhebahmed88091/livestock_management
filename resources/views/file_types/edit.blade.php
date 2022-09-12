@extends('layouts.app')

@section('content-header')
    <h1>Edit File Type</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('file_types.index') }}">
                <i class="fa fa-dashboard"></i> File Types
            </a>
        </li>
        <li class="active">{{__('commons.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($fileType->name) ? ucfirst($fileType->name) : 'File Type' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('file_types.index') }}" class="btn btn-sm btn-info"
                   title="Show All File Type">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('file_types.create') }}" class="btn btn-sm btn-success"
                   title="Create New File Type">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('file_types.update', $fileType->id) }}"
              id="edit_file_type_form"
              name="edit_file_type_form" accept-charset="UTF-8" >
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

                @include ('file_types.form', ['fileType' => $fileType,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection
