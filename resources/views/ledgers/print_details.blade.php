@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('ledgers.ledger_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.type')}}</th>
            <td>{{ $ledger->type }}</td>
        </tr>

        <tr>
            <th>{{__('commons.date')}}</th>
            <td>{{ $ledger->date }}</td>
        </tr>

        <tr>
            <th>{{__('ledgers.amount')}}</th>
            <td>{{ $ledger->amount }}</td>
        </tr>

        <tr>
            <th>{{__('commons.tag')}}</th>
            <td>{{ optional($ledger->tag)->title }}</td>
        </tr>

        <tr>
            <th>{{__('commons.details')}}</th>
            <td>{{ $ledger->details }}</td>
        </tr>

        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ optional($ledger->creator)->name }}</td>
        </tr>

        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $ledger->created_at }}</td>
        </tr>

        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $ledger->updated_at }}</td>
        </tr>
        </tbody>
    </table>

@endsection
