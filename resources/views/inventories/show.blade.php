@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.inventory_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventories.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventories')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($inventory->name) ? ucfirst($inventory->name) : 'Inventory' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('inventories.destroy', $inventory->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('inventories.index'))
                        <a href="{{ route('inventories.index') }}" class="btn btn-sm btn-info"
                           title="{{__('buttonTitle.show_all_inventory')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventories.printDetails'))
                        <a href="{{ route('inventories.printDetails', $inventory->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventories.create'))
                        <a href="{{ route('inventories.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_inventory')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventories.edit'))
                        <a href="{{ route('inventories.edit', $inventory->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_inventory')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('inventories.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_inventory')}}"
                                onclick="return confirm('Delete Inventory?')">
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
                        <th>{{__('commons.name')}}</th>
                        <td>{{ $inventory->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('inventory.inventory_image')}}</th>
                        <td>
                            @if (!empty($inventory->inventory_image))
                                <img src="{{ asset('storage/' . $inventory->inventory_image) }}"
                                     alt="Inventory Image" class="thumbnail medium"/>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{__('inventory.inventory_type')}}</th>
                        <td>{{ optional($inventory->inventoryType)->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('inventory.inventory_unit')}}</th>
                        <td>{{ optional($inventory->inventoryUnit)->title }}</td>
                    </tr>
                    <tr>
                        <th>{{__('inventory.source')}}</th>
                        <td>{{ $inventory->source }}</td>
                    </tr>
                    <tr>
                        <th>{{__('inventory.warranty')}}</th>
                        <td>{{ $inventory->warranty }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.description')}}</th>
                        <td>{{ $inventory->description }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.instruction')}}</th>
                        <td>{{ $inventory->instruction }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ optional($inventory->creator)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $inventory->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $inventory->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
