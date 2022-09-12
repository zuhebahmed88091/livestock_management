@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Country Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('countries.country_name')}}</th>
            <td>{{ $country->country_name }}</td>
        </tr>

        <tr>
            <th>{{__('countries.country_code')}}</th>
            <td>{{ $country->country_code }}</td>
        </tr>

        <tr>
            <th>{{__('countries.currency_code')}}</th>
            <td>{{ $country->currency_code }}</td>
        </tr>

        <tr>
            <th>{{__('countries.capital')}}</th>
            <td>{{ $country->capital }}</td>
        </tr>

        <tr>
            <th>{{__('countries.continent_name')}}</th>
            <td>{{ $country->continent_name }}</td>
        </tr>

        <tr>
            <th>{{__('countries.continent_code')}}</th>
            <td>{{ $country->continent_code }}</td>
        </tr>

        <tr>
            <th>{{__('commons.status')}}</th>
            <td>{{ $country->status }}</td>
        </tr>


        </tbody>
    </table>

@endsection
