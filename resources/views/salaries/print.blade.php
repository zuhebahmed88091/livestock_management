@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Salary List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('employees.employee')}}</th>
                <th>{{__('salaries.salary_date')}}</th>
                <th class="text-right">{{__('ledgers.amount')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ optional($item->user)->name }}</td>
                        <td>{{ $item->salary_date }}</td>
                        <td class="text-right">{{ $item->amount }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
