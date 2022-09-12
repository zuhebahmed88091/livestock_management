@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Inventory List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.name')}}</th>
                <th>{{__('inventory.inventory_type')}}</th>
                <th>{{__('inventory.inventory_unit')}}</th>
                <th>{{__('inventory.source')}}</th>
                <th>{{__('inventory.warranty')}}</th>
            </tr>
            </thead>
            <tbody>
            @php($serial = 1)
            @foreach($items as $item)
                <tr>
                    <td>{{ $serial++ }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ optional($item->inventoryType)->title }}</td>
                    <td>{{ optional($item->inventoryUnit)->title }}</td>
                    <td>{{ $item->source }}</td>
                    <td>{{ $item->warranty }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
