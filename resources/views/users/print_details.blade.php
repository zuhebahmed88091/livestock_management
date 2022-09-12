@extends('layouts.pdf')

@section('content')
    <h3 class="pdf-title">User Details</h3>
    <table class="table table-bordered table-show">
        <tbody>
        <tr>
            <th>{{__('commons.photo')}}</th>
            <td>
                <img src="{{ $_SERVER['DOCUMENT_ROOT'] . '/storage/profiles/' . optional($user->uploadedFile)->filename }}"
                     alt="Profile Image"
                     class="pdf-image">
            </td>
        </tr>
        <tr>
            <th>{{__('commons.name')}}</th>
            <td>{{ $user->name }}</td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.email')}}</th>
            <td>{{ $user->email }}</td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.email_verified_at')}}</th>
            <td>{{ $user->email_verified_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.status')}}</th>
            <td>{{ $user->status }}</td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.roles')}}</th>
            <td>
                {{ implode(', ', $user->roles()->pluck('name')->toArray()) }}
            </td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.country')}}</th>
            <td>{{ optional($user->country)->country_name }}</td>
        </tr>
        <tr>
            <th>{{__('adminPrivilege.phone')}}</th>
            <td>{{ $user->phone }}</td>
        </tr>
        <tr>
            <th>{{__('commons.created_at')}}</th>
            <td>{{ $user->created_at }}</td>
        </tr>
        <tr>
            <th>{{__('commons.update_at')}}</th>
            <td>{{ $user->updated_at }}</td>
        </tr>

        </tbody>
    </table>

@endsection
