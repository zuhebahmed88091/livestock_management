@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('employees.daily_wage_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('employees.labour')}}</th>
            <td>{{ optional($dailyWage->user)->name }}</td>
        </tr>
        <tr>
            <th>{{__('employees.wages')}}</th>
            <td>{{ $dailyWage->wages }}</td>
        </tr>
        <tr>
            <th>{{__('employees.paying_status')}}</th>
            <td>{{ $dailyWage->paying_status }}</td>
        </tr>
        <tr>
            <th>{{__('employees.work_date')}}</th>
            <td>{{ $dailyWage->work_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $dailyWage->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $dailyWage->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $dailyWage->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
