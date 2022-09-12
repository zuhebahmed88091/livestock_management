@extends('layouts.app')

@section('content-header')
    <h1>{{__('tag.tag_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('tags.index') }}">
                <i class="fa fa-dashboard"></i> {{__('tag.tags')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($tag->title) ? ucfirst($tag->title) : 'Tag' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('tags.destroy', $tag->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('tags.index'))
                        <a href="{{ route('tags.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_tag')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('tags.create'))
                        <a href="{{ route('tags.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_tag')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('tags.edit'))
                        <a href="{{ route('tags.edit', $tag->id ) }}"
                           class="btn btn-sm btn-primary" title="Edit Tag">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('tags.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_tag')}}"
                                onclick="return confirm('Delete Tag?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>{{__('commons.title')}}</th>
                        <td>{{ $tag->title }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ optional($tag->creator)->name }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $tag->created_at }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $tag->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
