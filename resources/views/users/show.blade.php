@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.user_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('users.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.users')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($user->name) ? ucfirst($user->name) : 'User' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('users.destroy', $user->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('users.index'))
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_user')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.printDetails'))
                        <a href="{{ route('users.printDetails', $user->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.create'))
                        <a href="{{ route('users.create') }}" class="btn btn-sm btn-success" title="{{__('buttonTitle.create_new_user')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.edit'))
                        <a href="{{ route('users.edit', $user->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_user')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('users.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_user')}}"
                                onclick="return confirm('Delete User?')">
                            <i aria-hidden="true" class="fa fa-trash"></i>
                        </button>
                    @endif

                </form>

            </div>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered table-show">
                    <tbody>
                    <tr>
                        <th>{{__('commons.photo')}}</th>
                        <td>
                            <img src="{{ asset('storage/profiles/' . optional($user->uploadedFile)->filename) }}"
                                 alt="Profile Image"
                                 class="profile-image">
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
            </div>
        </div>
    </div>

@endsection
