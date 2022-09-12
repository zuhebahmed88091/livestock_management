@extends('layouts.app')

@section('content-header')
    <h1>{{__('sheds.c_type_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('sheds.index') }}">
                <i class="fa fa-dashboard"></i> {{__('sheds.sheds')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset ($shed->livestockType->title) ? ucfirst($shed->livestockType->title) : 'Shed' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('sheds.destroy', $shed->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('sheds.index'))
                        <a href="{{ route('sheds.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_sheds')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('sheds.create'))
                        <a href="{{ route('sheds.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_shed')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('sheds.edit'))
                        <a href="{{ route('sheds.edit', $shed->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_shed')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('sheds.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_shed')}}"
                                onclick="return confirm('Delete Shed?')">
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
                        <th>{{__('commons.shed_no')}}</th>
                        <td>{{ $shed->shed_no }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.batch_name')}}</th>
                        <td>{{ optional($shed->liveStock)->batch_name}}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.livestock_type')}}</th>
                        <td>{{ optional(optional($shed->liveStock)->livestockType)->title }}</td>
                    </tr>
                    
                    <tr>
                        <th>{{__('commons.quantity')}}</th>
                        <td>{{ $shed->quantity }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.age')}}</th>
                        <td>{{ $shed->age }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ ($shed->creator)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.purchase_date')}}</th>
                        <td>{{ $shed->purchase_date }}</td>
                    </tr>
                    
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
