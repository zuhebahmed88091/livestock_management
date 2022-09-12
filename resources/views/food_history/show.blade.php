@extends('layouts.app')

@section('content-header')
    <h1>{{__('food_history.c_type_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('foodHistory.index') }}">
                <i class="fa fa-dashboard"></i> {{__('food_history.food_history')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($foodHistory->food_name) ? ucfirst($foodHistory->food_name) : 'Food History' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('foodHistory.destroy', $foodHistory->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('foodHistory.index'))
                        <a href="{{ route('foodHistory.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_foodHistory')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif


                    @if (App\Helpers\CommonHelper::isCapable('foodHistory.create'))
                        <a href="{{ route('foodHistory.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_foodHistory')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('foodHistory.edit'))
                        <a href="{{ route('foodHistory.edit', $foodHistory->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_foodHistory')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('foodHistory.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_foodHistory')}}"
                                onclick="return confirm('Delete Food History?')">
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
                            <th>{{__('commons.food_name')}}</th>
                            <td>{{ ($foodHistory->inventory)->title }}</td>
                        </tr>
                        <tr>
                            <th>{{__('commons.batch_name')}}</th>
                            <td>{{ ($foodHistory->liveStock)->batch_name }}</td>
                        </tr>
                        <tr>
                            <th>{{__('commons.livestock_type')}}</th>
                            <td>{{ optional(optional($foodHistory->liveStock)->livestockType)->title }}</td>
                        </tr>
                        <tr>
                            <th>{{__('commons.shed')}}</th>
                            <td>{{ ($foodHistory->shed)->shed_no }}</td>
                        </tr>
                        <tr>
                            <th>{{__('commons.consume_quantity')}}</th>
                            <td>{{ $foodHistory->consume_quantity }}</td>
                        </tr>
                        <tr>
                            <th>{{__('commons.duration')}}</th>
                            <td>{{ $foodHistory->duration }}</td>
                        </tr>
                        <tr>
                            <th>{{__('commons.date')}}</th>
                            <td>{{ $foodHistory->date }}</td>
                        </tr>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
