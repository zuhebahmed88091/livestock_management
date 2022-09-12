@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.inventory_type_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventory_types.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventory_types')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($inventoryType->title) ? ucfirst($inventoryType->title) : 'Inventory Type' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('inventory_types.destroy', $inventoryType->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('inventory_types.index'))
                        <a href="{{ route('inventory_types.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_inventory_type')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_types.create'))
                        <a href="{{ route('inventory_types.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_inventory_type')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_types.edit'))
                        <a href="{{ route('inventory_types.edit', $inventoryType->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_inventory_type')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_types.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_inventory_type')}}"
                                onclick="return confirm('Delete Inventory Type?')">
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
                        <td>{{ $inventoryType->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.status')}}</th>
                        <td>{{ $inventoryType->status }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $inventoryType->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $inventoryType->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
