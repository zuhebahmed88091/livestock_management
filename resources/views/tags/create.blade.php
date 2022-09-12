@extends('layouts.app')

@section('content-header')
    <h1>{{__('tag.create_tag')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('tags.index') }}">
                <i class="fa fa-dashboard"></i> {{__('tag.tags')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('tag.create_new_tag')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('tags.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_tag')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('tags.store') }}" id="create_tag_form"
              name="create_tag_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('tags.form', ['tag' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('tag.add_tag')}}</button>
            </div>
        </form>
    </div>

@endsection