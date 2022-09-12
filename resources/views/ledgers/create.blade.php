@extends('layouts.app')

@section('content-header')
    <h1>{{__('ledgers.create_ledger')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('ledgers.index') }}">
                <i class="fa fa-dashboard"></i> {{__('ledgers.ledgers')}}
            </a>
        </li>
        <li class="active">{{__('commons.create')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">

        <div class="box-header with-border">
            <h3 class="box-title">
                {{__('ledgers.create_new_ledger')}}
            </h3>

            <div class="box-tools pull-right">
                <a href="{{ route('ledgers.index') }}" class="btn btn-sm btn-info"
                   title="{{__('buttonTitle.show_all_ledger')}}">
                    <i class="fa fa-th-list" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('ledgers.store') }}" id="create_ledger_form"
              name="create_ledger_form" accept-charset="UTF-8" >
            {{ csrf_field() }}

            <div class="box-body">
                @if ($errors->any())
                    <ul class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                @include ('ledgers.form', ['ledger' => null,])
            </div>

            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right">{{__('ledgers.add_ledger')}}</button>
            </div>
        </form>
    </div>

@endsection