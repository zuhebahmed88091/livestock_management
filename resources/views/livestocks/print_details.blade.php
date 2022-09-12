@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('livestocks.c_type_details')}}</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.title')}}</th>
            <td>{{ $liveStock->livestock_name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.quantity')}}</th>
            <td>{{ $liveStock->quantity }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $liveStock->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $liveStock->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
