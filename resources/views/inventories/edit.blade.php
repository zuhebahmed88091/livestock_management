@extends('layouts.app')

@section('content-header')
    <h1>{{__('inventory.edit_inventory')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('inventories.index') }}">
                <i class="fa fa-dashboard"></i> {{__('inventory.inventories')}}
            </a>
        </li>
        <li class="active">{{__('commons.edit')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ !empty($inventory->name) ? ucfirst($inventory->name) : 'Inventory' }}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('inventories.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_inventory')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>

                <a href="{{ route('inventories.create') }}" class="btn btn-sm btn-success"
                   title="{{__('buttonTitle.create_new_inventory')}}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST"
              action="{{ route('inventories.update', $inventory->id) }}"
              id="edit_inventory_form"
              name="edit_inventory_form" accept-charset="UTF-8"  enctype="multipart/form-data">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <div class="box-body">

                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('inventories.form', ['inventory' => $inventory,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('commons.update')}}</button>
            </div>
        </form>

    </div>

@endsection