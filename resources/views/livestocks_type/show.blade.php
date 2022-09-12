@extends('layouts.app')

@section('content-header')
    <h1>{{__('livestocks_type.livestock_type_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('livestocks_types.index') }}">
                <i class="fa fa-dashboard"></i> {{__('livestocks_type.livestock_type')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($liveStocksTypes->title) ? ucfirst($liveStocksTypes->title) : 'Livestock Type' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('livestocks_types.destroy', $liveStocksTypes->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('livestocks_types.index'))
                        <a href="{{ route('livestocks_types.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_livestock_type')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('livestocks_types.create'))
                        <a href="{{ route('livestocks_types.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_livestock_type')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('livestocks_types.edit'))
                        <a href="{{ route('livestocks_types.edit', $liveStocksTypes->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_livestock_type')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('livestocks_types.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_livestock_type')}}"
                                onclick="return confirm('Delete Livestock Type?')">
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
                        <td>{{ $liveStocksTypes->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.status')}}</th>
                        <td>{{ $liveStocksTypes->status }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $liveStocksTypes->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.updated_at')}}</th>
                        <td>{{ $liveStocksTypes->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
