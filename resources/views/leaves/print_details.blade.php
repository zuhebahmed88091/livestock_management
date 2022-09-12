@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Leave Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('employees.employee')}}</th>
            <td>{{ optional($leave->user)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.start_date')}}</th>
            <td>{{ $leave->start_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.end_date')}}</th>
            <td>{{ $leave->end_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $leave->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $leave->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $leave->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
