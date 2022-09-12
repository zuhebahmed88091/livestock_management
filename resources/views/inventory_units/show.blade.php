@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.inventory_unit_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventory_units.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventory_units')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($inventoryUnit->title) ? ucfirst($inventoryUnit->title) : 'Inventory Unit' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('inventory_units.destroy', $inventoryUnit->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('inventory_units.index'))
                        <a href="{{ route('inventory_units.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_inventory_unit')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_units.create'))
                        <a href="{{ route('inventory_units.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_inventory_unit')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_units.edit'))
                        <a href="{{ route('inventory_units.edit', $inventoryUnit->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_inventory_unit')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventory_units.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_inventory_unit')}}"
                                onclick="return confirm('Delete Inventory Unit?')">
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
                        <td>{{ $inventoryUnit->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.status')}}</th>
                        <td>{{ $inventoryUnit->status }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $inventoryUnit->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $inventoryUnit->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
