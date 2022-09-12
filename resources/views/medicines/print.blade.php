@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Medicine List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.cattle')}}</th>
                <th>{{__('medicine.doctor')}}</th>
                <th>{{__('commons.identify_date')}}</th>
                <th>{{__('commons.start_date')}}</th>
                <th>{{__('commons.end_date')}}</th>
                <th>{{__('commons.next_follow_up_date')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ optional($item->cattle)->title }}</td>
                        <td>{{ optional($item->doctor)->name }}</td>
                        <td>{{ $item->identify_date }}</td>
                        <td>{{ $item->start_date }}</td>
                        <td>{{ $item->end_date }}</td>
                        <td>{{ $item->next_follow_up_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
