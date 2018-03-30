const Swal = require('sweetalert2');

function createFieldRow(field) {
    var output = '<tr class="field-' + field.id + '">' +
                        '<td class="field-row-title">' + field.title + '</td>' + 
                        '<td>' + field.type + '</td>' + 
                        '<td>' + field.name + '</td>' + 
                        '<td>' + 
                            '<a href="#" class="btn btn-warning btn-update-field" ' +
                                'data-id="' + field.id + '" data-title="' + field.title + '" '+ 
                                'data-type="' + field.type + '" >edit</a>&nbsp;' +
                            '<a href="#" class="btn btn-danger btn-remove-field" ' +
                                'data-id="' + field.id + '">remove</a>&nbsp;' +
                    '</tr>';

    return output;
}

function processErrorFromAjax(jqXhr) {
    if(jqXhr.status == 422) {
        var errors = jqXhr.responseJSON.errors;

        var errorMessage = '';
        for(var key in errors) {
            if(errors.hasOwnProperty(key)) {
                errorMessage += errors[key][0] + '<br>';
            }
        }

        Swal({
            title: 'Something wrong',
            type: 'error',
            html: errorMessage
        })
    }
}

$.ajax({
    type: 'GET',
    url: '/api/field/list',
    headers: {
        'Authorization': 'Bearer ' + document.head.querySelector('meta[name="user-token"]').content
    },
    success: function(data) {
        var output = '';
        fields = data.fields;
        for(i = 0; i < fields.length; i++)  {
            output += createFieldRow(fields[i]);
        }

        $('.table-fields tbody').html(output);

    }
});

$(document).on('click', '.btn-update-field', function(e) {
    e.preventDefault();

    $('.modal-update-field').modal('show', $(e.target));
});

$(document).on('click', '.btn-add-field', function(e) {
    e.preventDefault();

    $('.modal-update-field').modal('show', $(e.target));
});

$(document).on('click', '.btn-remove-field', function(e) {
    e.preventDefault();

    var data_id = $(this).data('id');

    Swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if(result.value) {
            $.ajax({
                type: 'DELETE',
                headers: {
                    'Authorization': 'Bearer ' + document.head.querySelector('meta[name="user-token"]').content
                },
                url: '/api/field/remove/' + data_id,
                success: function(data) {
                    $('.field-' + data_id).remove();
                },
                error: function(jqXhr, textStatus, errorThrown) {
                    processErrorFromAjax(jqXhr);
                }
            })
        }
    });
});

$('.modal-update-field').on('show.bs.modal', function(e) {
    var button = $(e.relatedTarget);
    var data_id = button.data('id');
    var data_title = button.data('title');
    var data_type = button.data('type');

    var modal = $(this);

    if(data_id != undefined) {
        modal.find('#field-id').val(data_id);
        modal.find('#field-title').val(data_title);
        modal.find('#field-type').val(data_type.toLowerCase())
            .attr('disabled', 'disabled');

        modal.find('.modal-title').text('Update Field');
    } else {
        modal.find('.modal-title').text('Add Field');
    }
});

$('.modal-update-field').on('hide.bs.modal', function() {
    var modal = $(this);

    modal.find('#field-id').val('');
    modal.find('#field-title').val('');
    modal.find('#field-type').val('string')
        .removeAttr('disabled');
});

$('.update-field-form').on('submit', function(e) {
    e.preventDefault();

    var data_id = $('#field-id').val();
    var data_title = $('#field-title').val();
    var data_type = $('#field-type').val();

    if(data_id > 0) {
        // update
        $.ajax({
            type: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + document.head.querySelector('meta[name="user-token"]').content
            },
            url: '/api/field/update/' + data_id,
            data: {
                title: $('#field-title').val()
            },
            success: function(data) {
                var field = data.field;
                var fieldRow = $('.field-' + field.id);

                fieldRow.find('.field-row-title').text(field.title);

                $('.modal-update-field').modal('hide');
            },
            error: function(jqXhr, textStatus, errorThrown) {
                processErrorFromAjax(jqXhr);
            }
        });
    } else {
        // add
        $.ajax({
            type: 'POST',
            headers: {
                'Authorization': 'Bearer ' + document.head.querySelector('meta[name="user-token"]').content
            },
            url: '/api/field/add',
            data: {
                title: $('#field-title').val(),
                type: $('#field-type').val()
            },
            success: function(data) {
                output = createFieldRow(data.field);

                $(output).appendTo('.table-fields tbody');

                $('.modal-update-field').modal('hide');
            },
            error: function(jqXhr, textStatus, errorThrown) {
                processErrorFromAjax(jqXhr);
            }
        });
    }
});