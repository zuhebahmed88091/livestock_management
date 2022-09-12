@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Module Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.name')}}</th>
            <td>{{ $module->name }}</td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.slug')}}</th>
            <td>{{ $module->slug }}</td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.fa_icon')}}</th>
            <td>{{ $module->fa_icon }}</td>
        </tr>
        <tr>
            <th>{{__('commons.status')}}</th>
            <td>{{ $module->status }}</td>
        </tr>
        <tr>
            <th>{{__('commons.sorting')}}</th>
            <td>{{ $module->sorting }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $module->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $module->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
