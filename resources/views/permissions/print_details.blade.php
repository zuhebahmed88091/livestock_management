@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">Permission Details</h3>
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th width="25%">{{__('adminPrivilege.module')}}</th>
            <td width="75%">{{ optional($permission->module)->name }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.name')}}</th>
            <td width="75%">{{ $permission->name }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('adminPrivilege.display_name')}}</th>
            <td width="75%">{{ $permission->display_name }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.description')}}</th>
            <td width="75%">{{ $permission->description }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.created_at')}}</th>
            <td width="75%">{{ $permission->created_at }}</td>
        </tr>
        <tr>
            <th width="25%">{{__('commons.update_at')}}</th>
            <td width="75%">{{ $permission->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
