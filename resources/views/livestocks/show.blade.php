@extends('layouts.app')

@section('content-header')
    <h1>{{__('livestocks.c_type_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('livestocks.index') }}">
                <i class="fa fa-dashboard"></i> {{__('livestocks.livestocks')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($liveStock->livestock_name) ? ucfirst($liveStock->livestock_name) : 'Livestock' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('livestocks.destroy', $liveStock->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('livestocks.index'))
                        <a href="{{ route('livestocks.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_livestocks')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('livestocks.create'))
                        <a href="{{ route('livestocks.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_livestock')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('livestocks.edit'))
                        <a href="{{ route('livestocks.edit', $liveStock->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_livestock')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('livestocks.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_livestock')}}"
                                onclick="return confirm('Delete Livestock?')">
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
                        <th>{{__('commons.batch_name')}}</th>
                        <td>{{ $liveStock->batch_name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.livestock_type')}}</th>
                        <td>{{ ($liveStock->livestockType)->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.quantity')}}</th>
                        <td>{{ $liveStock->quantity }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.date')}}</th>
                        <td>{{ $liveStock->date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ $liveStock->creator->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $liveStock->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $liveStock->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
