@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('food_history.c_type_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
            <tr>
                <th>{{__('commons.livestock_type')}}</th>
                <td>{{ ($foodHistory->livestockType)->title }}</td>
            </tr>
            <tr>
                <th>{{__('commons.batch_name')}}</th>
                <td>{{ ($foodHistory->liveStock)->batch_name }}</td>
            </tr>
        <tr>
            <th>{{__('commons.shed')}}</th>
            <td>{{ ($foodHistory->shed)->shed_no }}</td>
        </tr>
        <tr>
            <th>{{__('commons.food_name')}}</th>
            <td>{{ ($foodHistory->inventory)->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.consume_quantity')}}</th>
            <td>{{ $foodHistory->consume_quantity }}</td>
        </tr>
        
        

        </tbody>
    </table>

@endsection
