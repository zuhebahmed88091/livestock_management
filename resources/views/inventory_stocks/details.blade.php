@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('content-header')
    <h1>{{__('inventory.inventory_stocks')}}</h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{__('inventory.inventory_stocks')}}</a></li>
        <li class="active">{{__('commons.details')}}</li>
    </ol>
@endsection

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">

                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped w-100">
                            <thead>
                            <tr>
                                <td class="text-center">{{__('commons.action')}}</td>
                                <th>{{__('commons.date')}}</th>
                                <th>{{__('commons.added_by')}}</th>
                                <th class="text-right">{{__('inventory.added_amount')}}</th>
                                <th class="text-right">{{__('inventory.consume_amount')}}</th>
                                <th class="text-right">{{__('inventory.balance')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($inventoryStocks as $inventoryStock)
                                <tr>
                                    <td class="text-center">
                                        <form method="POST"
                                              action="{!! route('inventory_stocks.destroy', $inventoryStock->id) !!}"
                                              accept-charset="UTF-8">
                                            <input name="_method" value="DELETE" type="hidden">
                                            {{ csrf_field() }}

                                            @if (App\Helpers\CommonHelper::isCapable('inventory_stocks.destroy'))
                                                <button type="submit" class="btn btn-xs btn-danger"
                                                        title="Delete Inventory Stock"
                                                        onclick="return confirm('Delete Inventory Stock?')">
                                                    <i aria-hidden="true" class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </form>

                                    </td>
                                    <td>{{ $inventoryStock->created_at }}</td>
                                    <td>{{ optional($inventoryStock->creator)->name }}</td>
                                    <td class="text-right">
                                        @if ($inventoryStock->quantity > 0)
                                            {{ $inventoryStock->quantity }} {{ $selectedInventory->unit }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if ($inventoryStock->quantity < 0)
                                            {{ abs($inventoryStock->quantity) }} {{ $selectedInventory->unit }}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {{ $cumulativeSum = $cumulativeSum + $inventoryStock->quantity }}
                                        {{ $selectedInventory->unit }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">
                                    {{ $selectedInventory->totalStockQuantity }}
                                </th>
                                <th class="text-right">
                                    {{ $selectedInventory->totalConsumeQuantity }}
                                </th>
                                <th class="text-right">
                                    {{ $selectedInventory->currentStockQuantity }}
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

<!-- page script -->
@section('javascript')
    <script src="{{ asset('bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script>
        $(function () {
            // Date range as a button
            // let start = moment().subtract(29, 'days');
            // let end = moment();
            "use strict";
            let dataTable = $('#dataTable').DataTable({
                "paging": false,
                "ordering": false,
                "bInfo": false,
                initComplete: function () {
                    $('.col-sm-6:first-child').append(
                        '@if (App\Helpers\CommonHelper::isCapable('inventory_stocks.index'))' +
                        '<a href="{{ route('inventory_stocks.index') }}" ' +
                        'class="btn btn-sm btn-primary" ' +
                        'title="Create New User"> ' +
                        '<i aria-hidden="true" class="fa fa-reply"></i> Go Back' +
                        '</a>' +
                        '<a href="{{ route('inventory_stocks.printDetails', $selectedInventory->id) }}" class="btn btn-sm btn-warning ml-5"\n' +
                        '  title="Print Details">\n' +
                        '     <i class="fa fa-file-pdf-o" aria-hidden="true"></i>\n' +
                        '   </a>' +
                        '@endif'
                    );
                }

            });

        });
    </script>
@endsection
