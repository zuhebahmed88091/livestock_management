@extends('layouts.app')

@section('content-header')
    <h1>{{__('adminPrivilege.assign_permissions_for')}} {{ $role->name }}</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('roles.index') }}">
                <i class="fa fa-dashboard"></i> {{__('adminPrivilege.roles')}}
            </a>
        </li>
        <li class="active">{{__('adminPrivilege.assign_permissions')}}</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-6">
            @for ($i = 0, $n = ceil(count($modules) / 2); $i < $n; $i++)
                @include ('roles.box_assign_permissions', ['module' => $modules[$i]])
            @endfor
        </div>

        <div class="col-md-6">
            @for ($i = $n, $m = count($modules); $i < $m; $i++)
                @include ('roles.box_assign_permissions', ['module' => $modules[$i]])
            @endfor
        </div>
    </div>

@endsection

<!-- iCheck -->
@section('javascript')
    <script>

        $(document).ready(function () {
            "use strict";
            // Enable iCheck plugin for checkboxes
            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });

            // Enable check and uncheck all functionality
            $(".btn-checkbox-toggle").on('click',function () {
                let moduleName = $(this).parent().prop('id');
                let isChecked = $(this).data('isChecked');
                let objCheckBoxes = $('.' + moduleName + ' input[type=checkbox]');
                if (isChecked) {
                    // Uncheck all checkboxes
                    objCheckBoxes.iCheck("uncheck");
                    $(".fa", this)
                        .removeClass("fa-minus-square fa-check-square")
                        .addClass('fa-square');
                } else {
                    // Check all checkboxes
                    objCheckBoxes.iCheck("check");
                    $(".fa", this)
                        .removeClass("fa-minus-square fa-square")
                        .addClass('fa-check-square');
                }
                $(this).data("isChecked", !isChecked);
                objCheckBoxes.data("isChecked", !isChecked);
            });

            $("input[type=checkbox]").on("ifClicked", function () {
                // update check box
                $(this).iCheck('toggle');

                // checking whether all options are checked
                let moduleName = $(this).prop('class');
                let objCheckBoxes = $('.' + moduleName + ' input[type=checkbox]');
                let totalOptions = objCheckBoxes.length;
                let checkedOptions = objCheckBoxes.filter(':checked').length;
                let objCheckAllBtn = $('#' + moduleName + ' .btn-checkbox-toggle');
                if (totalOptions === checkedOptions) {
                    objCheckAllBtn.data("isChecked", true);
                    $(".fa", objCheckAllBtn)
                        .removeClass('fa-minus-square fa-square')
                        .addClass('fa-check-square');
                } else if (checkedOptions > 0) {
                    $(".fa", objCheckAllBtn)
                        .removeClass('fa-square fa-check-square')
                        .addClass('fa-minus-square');
                } else {
                    objCheckAllBtn.data("isChecked", false);
                    $(".fa", objCheckAllBtn)
                        .removeClass('fa-minus-square fa-check-square')
                        .addClass('fa-square');
                }
            });

            $(".btn-save").on('click',function () {
                let moduleId = $(this).prop('id');
                let moduleName = $(this).parent().prop('id');
                let permissionIds = [];
                $('.' + moduleName + ' input:checked').each(function () {
                    permissionIds.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: '{{ url('/roles/assign_permissions') }}',
                    data: {
                        'roleId': '{{ $role->id }}',
                        'moduleId': moduleId,
                        'permissionIds': permissionIds,
                        '_token': '{{ csrf_token() }}'
                    },
                    dataType: "text",
                    beforeSend: function () {
                        $('#msg-' + moduleName)
                            .removeClass('label-success label-danger')
                            .html('<img src="{{ asset('images/ajax-loader-24.gif') }}">')
                            .fadeIn(50);
                    },
                    success: function (jsonString) {
                        let jsonObject = JSON.parse(jsonString);
                        if (jsonObject.status === 'OK') {
                            $('#msg-' + moduleName)
                                .removeClass('label-danger')
                                .addClass('label-success')
                                .html(jsonObject.message)
                                .fadeIn(1500)
                                .fadeOut(3500);
                        }
                    },
                    error: function (xhr) {
                        $('#msg-' + moduleName)
                            .removeClass('label-success')
                            .addClass('label-danger')
                            .html(xhr.statusText)
                            .fadeIn(1500)
                            .fadeOut(3500);
                    }
                });
            });

            // for loading default value
            // https://api.jquery.com/val/
            //$('input[name="test"]').val(arrayValues);
        });

    </script>
@endsection
