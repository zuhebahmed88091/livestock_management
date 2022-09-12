@extends('layouts.app')

@section('content-header')
    <h1>{{__('medicine.medicine_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('medicines.index') }}">
                <i class="fa fa-dashboard"></i> {{__('medicine.medicines')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Medicine' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('medicines.destroy', $medicine->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('medicines.index'))
                        <a href="{{ route('medicines.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_medicine')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif
                    
                    @if (App\Helpers\CommonHelper::isCapable('medicines.create'))
                        <a href="{{ route('medicines.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create-new_medicine')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('medicines.edit'))
                        <a href="{{ route('medicines.edit', $medicine->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_medicine')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('medicines.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_medicine')}}"
                                onclick="return confirm('Delete Medicine?')">
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
                            <th>{{__('commons.batch_name')}}</th>
                            <td>{{ optional($medicine->liveStock)->batch_name }}</td>
                        </tr>  
                        <tr>
                            <th>{{__('commons.livestock_type')}}</th>
                            <td>{{ optional(optional($medicine->liveStock)->livestockType)->title }}</td>
                        </tr> 
                    <tr>
                        <th>{{__('commons.shed_no')}}</th>
                        <td>{{ optional($medicine->shed)->shed_no }}</td>
                    </tr>
                    <tr>
                        <th>{{__('medicine.doctor')}}</th>
                        <td>{{ optional($medicine->doctor)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.identify_date')}}</th>
                        <td>{{ $medicine->identify_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.start_date')}}</th>
                        <td>{{ $medicine->start_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.end_date')}}</th>
                        <td>{{ $medicine->end_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.next_follow_up_date')}}</th>
                        <td>{{ $medicine->next_follow_up_date }}</td>
                    </tr>
                    <tr>
                        <th>{{__('medicine.special_dose')}}</th>
                        <td>{{ $medicine->special_dose }}</td>
                    </tr>
                    <tr>
                        <th>{{__('medicine.regular_dose')}}</th>
                        <td>{{ $medicine->regular_dose }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.comments')}}</th>
                        <td>{{ $medicine->comments }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ optional($medicine->creator)->name }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $medicine->created_at }}</td>
                    </tr>
                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $medicine->updated_at }}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
