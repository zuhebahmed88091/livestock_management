@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Salary Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('employees.employee')}}</th>
            <td>{{ optional($salary->user)->name }}</td>
        </tr>
        <tr>
            <th>{{__('salaries.salary_date')}}</th>
            <td>{{ $salary->salary_date }}</td>
        </tr>
        <tr>
            <th>{{__('ledgers.amount')}}</th>
            <td>{{ $salary->amount }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $salary->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $salary->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $salary->updated_at }}</td>
        </tr>
        </tbody>
    </table>

@endsection
