@extends('layouts.app')

@section('content-header')
    <h1>{{__('ledgers.ledger_details')}}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('ledgers.index') }}">
                <i class="fa fa-dashboard"></i> {{__('ledgers.ledgers')}}
            </a>
        </li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    <div class="box box-default">
        <div class="box-header with-border">
            <h3 class="box-title">
                {{ isset($title) ? ucfirst($title) : 'Ledger' }} {{__('commons.full_information')}}
            </h3>

            <div class="box-tools pull-right">

                <form method="POST"
                      action="{!! route('ledgers.destroy', $ledger->id) !!}"
                      accept-charset="UTF-8">
                    <input name="_method" value="DELETE" type="hidden">
                    {{ csrf_field() }}

                    @if (App\Helpers\CommonHelper::isCapable('ledgers.index'))
                        <a href="{{ route('ledgers.index') }}" class="btn btn-sm btn-info" title="{{__('buttonTitle.show_all_ledger')}}">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('ledgers.printDetails'))
                        <a href="{{ route('ledgers.printDetails', $ledger->id) }}" class="btn btn-sm btn-warning"
                           title="{{__('buttonTitle.print_details')}}">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('ledgers.create'))
                        <a href="{{ route('ledgers.create') }}" class="btn btn-sm btn-success"
                           title="{{__('buttonTitle.create_new_ledger')}}">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('ledgers.edit'))
                        <a href="{{ route('ledgers.edit', $ledger->id ) }}"
                           class="btn btn-sm btn-primary" title="{{__('buttonTitle.edit_ledger')}}">
                            <i aria-hidden="true" class="fa fa-pencil"></i>
                        </a>
                    @endif

                    @if (App\Helpers\CommonHelper::isCapable('ledgers.destroy'))
                        <button type="submit" class="btn btn-sm btn-danger"
                                title="{{__('buttonTitle.delete_ledger')}}"
                                onclick="return confirm('Delete Ledger?')">
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
                        <th>{{__('commons.type')}}</th>
                        <td>{{ $ledger->type }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.date')}}</th>
                        <td>{{ $ledger->date }}</td>
                    </tr>

                    <tr>
                        <th>{{__('ledgers.amount')}}</th>
                        <td>{{ $ledger->amount }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.tag')}}</th>
                        <td>{{ optional($ledger->tag)->title }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.details')}}</th>
                        <td>{{ $ledger->details }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.created_by')}}</th>
                        <td>{{ optional($ledger->creator)->name }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.created_at')}}</th>
                        <td>{{ $ledger->created_at }}</td>
                    </tr>

                    <tr>
                        <th>{{__('commons.update_at')}}</th>
                        <td>{{ $ledger->updated_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
