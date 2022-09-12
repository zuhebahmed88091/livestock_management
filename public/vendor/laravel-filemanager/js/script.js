// Initialize order by obj
let orderByObj = $('#order_by');
let sortDirectionObj = $('#sort_direction');

// Initialize sort type
let sort_type = localStorage.getItem('sort_type');
if (sort_type === undefined || sort_type === null) {
    sort_type = 'alphabetic';
}
orderByObj.val(sort_type);

// Initialize sort direction
let sort_direction = localStorage.getItem('sort_direction');
if (sort_direction === undefined || sort_direction === null) {
    sort_direction = 'ASC';
}
sortDirectionObj.val(sort_direction);

// Initialize list type (0 = thumbnail, 1 = list)
let list_type = localStorage.getItem('list_type');
if (list_type === undefined || list_type === null) {
    list_type = 0;
}

$(document).ready(function () {
    bootbox.setDefaults({locale: lang['locale-bootbox']});
    loadFolders();
    performLfmRequest('errors')
        .done(function (data) {
            let response = JSON.parse(data);
            for (let i = 0; i < response.length; i++) {
                $('#alerts').append(
                    $('<div>').addClass('alert alert-warning')
                        .append($('<i>').addClass('fa fa-exclamation-circle'))
                        .append(' ' + response[i])
                );
            }
        });

    $(window).on('dragenter', function () {
        $('#uploadModal').modal('show');
    });
});

// Set list type to thumbnail
$('#thumbnail-display').click(function () {
    list_type = 0;
    localStorage.setItem('list_type', list_type);
    loadItems();
});

// Set list type to list
$('#list-display').click(function () {
    list_type = 1;
    localStorage.setItem('list_type', list_type);
    loadItems();
});

// Set sort type
orderByObj.change(function () {
    sort_type = orderByObj.val();
    localStorage.setItem('sort_type', sort_type);
    loadItems();
});

// Set sort direction
sortDirectionObj.change(function () {
    sort_direction = sortDirectionObj.val();
    localStorage.setItem('sort_direction', sort_direction);
    loadItems();
});

// ======================
// ==  Nav bar actions  ==
// ======================

$('#nav-buttons a').click(function (e) {
    e.preventDefault();
});

$('#to-previous').click(function () {
    let previous_dir = getPreviousDir();
    if (previous_dir == '') return;
    goTo(previous_dir);
});

$('#add-folder').click(function () {
    bootbox.prompt(lang['message-name'], function (result) {
        if (result == null) return;
        createFolder(result);
    });
});

$('#upload').click(function () {
    $('#uploadModal').modal('show');
});

$('#upload-btn').click(function () {
    $(this).html('')
        .append($('<i>').addClass('fa fa-refresh fa-spin'))
        .append(" " + lang['btn-uploading'])
        .addClass('disabled');

    function resetUploadForm() {
        $('#uploadModal').modal('hide');
        $('#upload-btn').html(lang['btn-upload']).removeClass('disabled');
        $('input#upload').val('');
    }

    $('#uploadForm').ajaxSubmit({
        success: function (data, statusText, xhr, $form) {
            resetUploadForm();
            refreshFoldersAndItems(data);
            displaySuccessMessage(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            displayErrorResponse(jqXHR);
            resetUploadForm();
        }
    });
});



// ======================
// ==  Folder actions  ==
// ======================

$(document).on('click', '.file-item', function (e) {
    useFile($(this).data('id'));
});

$(document).on('click', '.folder-item', function (e) {
    goTo($(this).data('id'));
});

function goTo(new_dir) {
    $('#working_dir').val(new_dir);
    loadItems();
}

function getPreviousDir() {
    let ds = '/';
    let working_dir = $('#working_dir').val();
    let last_ds = working_dir.lastIndexOf(ds);
    return  working_dir.substring(0, last_ds);
}

function dir_starts_with(str) {
    return $('#working_dir').val().indexOf(str) === 0;
}

function setOpenFolders() {
    let folders = $('.folder-item');
    for (let i = folders.length - 1; i >= 0; i--) {
        // close folders that are not parent
        if (!dir_starts_with($(folders[i]).data('id'))) {
            $(folders[i]).children('i').removeClass('fa-folder-open').addClass('fa-folder');
        } else {
            $(folders[i]).children('i').removeClass('fa-folder').addClass('fa-folder-open');
        }
    }
}

// ====================
// ==  Ajax actions  ==
// ====================

function performLfmRequest(url, parameter, type) {
    let data = defaultParameters();

    if (parameter != null) {
        $.each(parameter, function (key, value) {
            data[key] = value;
        });
    }

    return $.ajax({
        type: 'GET',
        dataType: type || 'text',
        url: lfm_route + '/' + url,
        data: data,
        cache: false
    }).fail(function (jqXHR, textStatus, errorThrown) {
        displayErrorResponse(jqXHR);
    });
}

function displayErrorResponse(jqXHR) {
    notify('<div style="max-height:50vh;overflow: scroll;">' + jqXHR.responseText + '</div>');
}

function displaySuccessMessage(data) {
    if (data == 'OK') {
        let success = $('<div>').addClass('alert alert-success')
            .append($('<i>').addClass('fa fa-check'))
            .append(' File Uploaded Successfully.');
        $('#alerts').append(success);
        setTimeout(function () {
            success.remove();
        }, 2000);
    }
}

let refreshFoldersAndItems = function (data) {
    loadFolders();
    if (data != 'OK') {
        data = Array.isArray(data) ? data.join('<br/>') : data;
        notify(data);
    }
};

let hideNavAndShowEditor = function (data) {
    $('#nav-buttons > ul').addClass('hidden');
    $('#content').html(data);
};

function loadFolders() {
    performLfmRequest('folders', {}, 'html')
        .done(function (data) {
            $('#tree').html(data);
            loadItems();
        });
}

function loadItems() {
    $('#lfm-loader').show();
    performLfmRequest('jsonitems', {show_list: list_type, sort_type: sort_type, sort_direction: sort_direction}, 'html')
        .done(function (data) {
            let response = JSON.parse(data);
            $('#content').html(response.html);
            $('#nav-buttons > ul').removeClass('hidden');
            $('#working_dir').val(response.working_dir);
            $('#current_dir').text(response.working_dir);
            if (getPreviousDir() == '') {
                $('#to-previous').addClass('hide');
            } else {
                $('#to-previous').removeClass('hide');
            }
            setOpenFolders();
        })
        .always(function () {
            $('#lfm-loader').hide();
        });
}

function createFolder(folder_name) {
    performLfmRequest('newfolder', {name: folder_name})
        .done(refreshFoldersAndItems);
}

function rename(item_name) {
    bootbox.prompt({
        title: lang['message-rename'],
        value: item_name,
        callback: function (result) {
            if (result == null) return;
            performLfmRequest('rename', {
                file: item_name,
                new_name: result
            }).done(refreshFoldersAndItems);
        }
    });
}

function trash(item_name) {
    bootbox.confirm(lang['message-delete'], function (result) {
        if (result == true) {
            performLfmRequest('delete', {items: item_name})
                .done(refreshFoldersAndItems);
        }
    });
}

function cropImage(image_name) {
    performLfmRequest('crop', {img: image_name})
        .done(hideNavAndShowEditor);
}

function resizeImage(image_name) {
    performLfmRequest('resize', {img: image_name})
        .done(hideNavAndShowEditor);
}

function download(file_name) {
    let data = defaultParameters();
    data['file'] = file_name;
    location.href = lfm_route + '/download?' + $.param(data);
}

// ==================================
// ==  Ckeditor, Bootbox, preview  ==
// ==================================

function useFile(file_url) {
    // Get file path
    let file_path = file_url.replace(route_prefix, '');

    // No editor found, open/download file using browser's default method
    //window.open(file_url);
    fileView(file_url);
}

function defaultParameters() {
    return {
        working_dir: $('#working_dir').val(),
        type: $('#type').val()
    };
}

function notify(message) {
    bootbox.alert(message);
}

function fileView(file_url, timestamp) {
    bootbox.dialog({
        title: lang['title-view'],
        message: $('<img>')
            .addClass('img img-responsive center-block')
            .attr('src', file_url + '?timestamp=' + timestamp),
        size: 'large',
        onEscape: true,
        backdrop: true
    });
}
