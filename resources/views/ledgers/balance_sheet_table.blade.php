<div class="pull-right balance-box">
    <b>{{__('ledgers.previous_balance')}} {{ $previousBalance }}</b>
</div>
<div class="clearfix"></div>

<table class="table table-striped table-show table-bordered">
    <thead>
    <tr>
        <th>{{__('commons.sl')}}</th>
        <th>{{__('commons.date')}}</th>
        <th>{{__('commons.title')}}</th>
        <th class="text-right">{{__('ledgers.credit')}}</th>
        <th class="text-right">{{__('ledgers.debit')}}</th>
        <th class="text-right">{{__('ledgers.balance')}}</th>
    </tr>
    </thead>
    <tbody>
    @php($serial = 1)
    @foreach($ledgers as $ledger)
        <tr>
            <td>{{ $serial++ }}</td>
            <td>{{ date('M d, Y', strtotime($ledger->created_at)) }}</td>
            <td>{{ optional($ledger->tag)->title }}</td>
            <td class="text-right">{{ $ledger->credit }}</td>
            <td class="text-right">{{ $ledger->debit }}</td>
            <td class="text-right">{{ $ledger->cumulativeSum }}</td>
        </tr>
    @endforeach
    </tbody>
    @if (count($ledgers)> 0)
        <tfoot>
        <tr>
            <th colspan="3">&nbsp;</th>
            <th class="text-right">{{ $totalCredit }}</th>
            <th class="text-right">{{ $totalDebit }}</th>
            <th class="text-right">{{ $cumulativeSum }}</th>
        </tr>
        </tfoot>
    @else
        <tr>
            <th colspan="6" class="text-center">{{__('ledgers.No_records_found')}}</th>
        </tr>
    @endif
</table>



