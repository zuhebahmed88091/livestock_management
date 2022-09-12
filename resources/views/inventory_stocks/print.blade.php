@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Inventory List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>Inventory</th>
                <th class="text-right">{{__('inventory.total_amount')}}</th>
                <th class="text-right">{{__('inventory.consume_amount')}}</th>
                <th class="text-right">{{__('inventory.stock_amount')}}</th>
                <th class="text-right">{{__('inventory.total_cost')}}</th>
                <th>{{__('commons.created_by')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td class="text-right">{{ $item->totalStockQuantity }}</td>
                        <td class="text-right">{{ $item->totalConsumeQuantity }}</td>
                        <td class="text-right">{{ $item->currentStockQuantity }}</td>
                        <td class="text-right">{{ $item->totalStockCost }}</td>
                        <td>{{ optional($item->creator)->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
