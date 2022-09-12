@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('ledgers.balance_sheet')}}</h3>
    @include('ledgers.balance_sheet_table')
@endsection
