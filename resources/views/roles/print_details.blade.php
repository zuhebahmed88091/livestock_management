@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">{{__('adminPrivilege.role_details')}}</h3>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th width="25%">{{__('commons.name')}}</th>
            <td width="75%">{{ $role->name }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('adminPrivilege.display_name')}}</th>
            <td width="75%">{{ $role->display_name }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.description')}}</th>
            <td width="75%">{{ $role->description }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.created_at')}}</th>
            <td width="75%">{{ $role->created_at }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.update_at')}}</th>
            <td width="75%">{{ $role->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
