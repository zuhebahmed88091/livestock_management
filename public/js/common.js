$(function () {
    'use strict';
    // convert select option to
    $('.select-admin-lte').select2().on('change', function () {
        $(this).valid();
    });

    // Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'M dd, yyyy'
    });

    // icheck for nice checkbox
    $('.checkbox.icheck input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%'
    });

    // Sets the validator defaults
    $.validator.setDefaults({
        errorElement: "span",
        errorClass: "help-block",
        ignore: ":hidden:not(textarea)",
        highlight: function (element, errorClass, validClass) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element, errorClass, validClass) {
            let elem = $(element);
            if (elem.prop('type') === 'search') {
                if (elem.val()) {
                    elem.closest('.form-group').removeClass('has-error');
                }
            } else {
                elem.closest('.form-group').removeClass('has-error');
            }
        },
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.prop('type') === 'select-one') {
                error.insertAfter(element.parent().find('span.select2'));
            } else if (element.prop('type') === 'select-multiple') {
                error.insertAfter(element.parent().find('span.select2'));
            } else if (element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
                error.appendTo(element.closest(':not(input, label, .checkbox, .radio)').first());
            } else if (element.prop('type') === 'textarea' && element.hasClass('rich-textarea') && editor) {
                //updateRichTextEditor();

                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    // Makes sure any input with the required class is actually required
    $('form').each(function (index, item) {
        let form = $(item);
        form.validate();

        form.find(':input.required').each(function (i, input) {
            $(input).attr('required', true);
        });
    });

    // We can attach the `fileselect` event to all file inputs on the page
    $(document).on('change', ':file', function () {
        let input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // We can watch for our custom `fileselect` event like this
    $(document).ready(function () {
        $(':file').on('fileselect', function (event, numFiles, label) {
            let input = $(this).parents('.input-group').find(':text'),
                log = numFiles > 1 ? numFiles + ' files selected' : label;

            if (input.length) {
                input.val(log);
                input.valid();
            }
        });
    });
});
