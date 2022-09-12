@extends('layouts.app')

@section('content-header')
    <h1>{{__('countries.country_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('countries.index') }}">
                <i class="fa fa-dashboard"></i> {{__('countries.countries')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Country' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('countries.destroy', $country->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('countries.index'))
                        <a href="{{ route('countries.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_country')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.printDetails'))
                        <a href="{{ route('countries.printDetails', $country->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.create'))
                        <a href="{{ route('countries.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_country')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.edit'))
                        <a href="{{ route('countries.edit', $country->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_country')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('countries.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_country')}}"
                                onclick="return confirm('Delete Country?')">
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
            </div>
        </div>
    </div>

@endsection
