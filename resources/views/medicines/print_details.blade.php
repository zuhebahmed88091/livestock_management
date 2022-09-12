@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Medicine Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.shed_no')}}</th>
            <td>{{ optional($medicine->shed)->shed_no }}</td>
        </tr>   
        <tr>
            <th>{{__('commons.livestock_type')}}</th>
            <td>{{ optional($medicine->livestockType)->title }}</td>
        </tr>
        <tr>
            <th>{{__('commons.batch_name')}}</th>
            <td>{{ optional($medicine->liveStock)->batch_name }}</td>
        </tr>
        <tr>
            <th>{{__('medicine.doctor')}}</th>
            <td>{{ optional($medicine->doctor)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.identify_date')}}</th>
            <td>{{ $medicine->identify_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.start_date')}}</th>
            <td>{{ $medicine->start_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.end_date')}}</th>
            <td>{{ $medicine->end_date }}</td>
        </tr>
        <tr>
            <th>{{__('commons.next_follow_up_date')}}</th>
            <td>{{ $medicine->next_follow_up_date }}</td>
        </tr>
        <tr>
            <th>{{__('medicine.special_dose')}}</th>
            <td>{{ $medicine->special_dose }}</td>
        </tr>
        <tr>
            <th>{{__('medicine.regular_dose')}}</th>
            <td>{{ $medicine->regular_dose }}</td>
        </tr>
        <tr>
            <th>{{__('commons.comments')}}</th>
            <td>{{ $medicine->comments }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_by')}}</th>
            <td>{{ optional($medicine->creator)->name }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $medicine->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $medicine->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
