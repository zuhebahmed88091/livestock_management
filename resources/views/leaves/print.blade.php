@extends('layouts.print')

@section('content')

    <div class="table-responsive">
        <h1 class="text-center">Leave List</h1>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
            <tr>
                <th>{{__('commons.sl')}}</th>
                <th>{{__('commons.labour')}}</th>
                <th>{{__('commons.start_date')}}</th>
                <th>{{__('commons.end_date')}}</th>
                <th class="text-right">{{__('commons.days')}}</th>
            </tr>
            </thead>
            <tbody>
                @php($serial = 1)
                @foreach($items as $item)
                    <tr>
                        <td>{{ $serial++ }}</td>
                        <td>{{ optional($item->user)->name }}</td>
                        <td>{{ $item->start_date }}</td>
                        <td>{{ $item->end_date }}</td>
                        <td class="text-right">{{ $item->days }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
