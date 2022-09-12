@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Stock Details for <b>{{ $selectedInventory->name }}</b></h3>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>{{__('commons.sl')}}</th>
            <th>{{__('commons.date')}}</th>
            <th>{{__('commons.added_by')}}</th>
            <th class="text-right">{{__('inventory.added_amount')}}</th>
            <th class="text-right">{{__('inventory.consume_amount')}}</th>
            <th class="text-right">{{__('inventory.balance')}}</th>
        </tr>
        </thead>
        <tbody>
        @php($serial = 1)
        @foreach($inventoryStocks as $inventoryStock)
            <tr>
                <td>{{ $serial++ }}</td>
                <td>{{ $inventoryStock->created_at }}</td>
                <td>{{ optional($inventoryStock->creator)->name }}</td>
                <td class="text-right">
                    @if ($inventoryStock->quantity > 0)
                        {{ $inventoryStock->quantity }} {{ $selectedInventory->unit }}
                    @else
                        --
                    @endif
                </td>
                <td class="text-right">
                    @if ($inventoryStock->quantity < 0)
                        {{ abs($inventoryStock->quantity) }} {{ $selectedInventory->unit }}
                    @else
                        --
                    @endif
                </td>
                <td class="text-right">
                    {{ $cumulativeSum = $cumulativeSum + $inventoryStock->quantity }}
                    {{ $selectedInventory->unit }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
