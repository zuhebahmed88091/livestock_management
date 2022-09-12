@extends('layouts.app')

@section('content-header')
    <h1>Create File Type</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('file_types.index') }}">
                <i class="fa fa-dashboard"></i> File Types
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                Create New File Type
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('file_types.index') }}" class="btn btn-sm btn-info"
                   title="Show All File Type">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('file_types.store') }}" id="create_file_type_form"
              name="create_file_type_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('file_types.form', ['fileType' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">Add fileType</button>
            </div>
        </form>
    </div>

@endsection
