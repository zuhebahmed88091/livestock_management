@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('sheds.c_type_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.title')}}</th>
            <td>{{ $shed->shed_no }}</td>
        </tr>
        <tr>
            <th>{{__('commons.livestock_type')}}</th>
            <td>{{ ($shed->livestockType)->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.batch_name')}}</th>
            <td>{{ ($shed->liveStock)->batch_name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.age')}}</th>
            <td>{{ $shed->age }}</td>
        </tr>
        <tr>
            <th>{{__('commons.quantity')}}</th>
            <td>{{ $shed->quantity }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ ($shed->creator)->name }}</td>
        </tr>
        

        </tbody>
    </table>

@endsection
