@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Daily Wage List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.user')}}</th>
                <th class="text-right">{{__('employees.wages')}}</th>
                <th>{{__('employees.paying_status')}}</th>
                <th>{{__('employees.work_date')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ optional($item->user)->name }}</td>
                        <td class="text-right">{{ $item->wages }}</td>
                        <td>{{ $item->paying_status }}</td>
                        <td>{{ $item->work_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
