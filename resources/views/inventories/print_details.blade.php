@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('inventory.inventory_details')}}</h3>
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
                    <img src="{{ $_SERVER["DOCUMENT_ROOT"] . '/storage/' . $inventory->inventory_image }}"
                         alt="Inventory Image" class="thumbnail medium" />
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

@endsection
