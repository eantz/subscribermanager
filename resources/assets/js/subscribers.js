const Swal = require('sweetalert2');

function createSubscriberRow(subscriber) {
    var buttonEdit = '<a href="#" class="btn btn-warning btn-update-subscriber" ' +
                        'data-id="' + subscriber.id + '" >edit</a>&nbsp;';

    var buttonDelete = '<a href="#" class="btn btn-danger btn-remove-subscriber" ' +
                        'data-id="' + subscriber.id + '">remove</a>&nbsp;';

    var output = '<tr class="subscriber-' + subscriber.id + '">' +
                        '<td class="subscriber-row-email">' + subscriber.email + '</td>' + 
                        '<td class="subscriber-row-name">' + subscriber.name + '</td>' + 
                        '<td>' + 
                            buttonEdit +
                            buttonDelete +
                    '</tr>';

    return output;
}

function createFormInput(field) {
    var inputType = field.type.toLowerCase();

    if(inputType == 'boolean') {
        var output = '<div class="form-group">' +
                        '<div class="form-check">' +
                            '<input class="form-check-input" type="checkbox" value="1" ' + 
                                'id="subscriber-' + field.name + '" ' +
                                'name="' + field.name + '" ' + 
                                (field.value == '1' ? 'checked="checked" ' : '') + '>' +
                            '<label class="form-check-label" for="subscriber-' + field.name + '">' +
                                field.title + '</label>' +
                        '</div>' +
                    '</div>';
    } else {
        var output = '<div class="form-group">' +
                        '<label class="form-label" for="subscriber-' + field.name +
                            '">' + field.title + '</label>' +
                        '<input type="' + (inputType == 'number' ? 'number' : 'text') + '" ' + 
                            'class="form-control" name="' + field.name + '" ' +
                            'id="subscriber-' + field.name + '" value="' + 
                            (field.value != undefined ? field.value : '') + '">' +
                    '</div>';
    }

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
    url: '/api/subscriber/list',
    headers: {
        'Authorization': 'Bearer ' + document.head.querySelector('meta[name="user-token"]').content
    },
    success: function(data) {
        var output = '';
        subscribers = data.subscribers;
        for(i = 0; i < subscribers.length; i++)  {
            output += createSubscriberRow(subscribers[i]);
        }

        console.log(output);

        $('.table-subscribers tbody').html(output);

    }
});

$(document).on('click', '.btn-add-subscriber', function(e) {
    e.preventDefault();

    $('.modal-update-subscriber').modal('show', $(e.target));
});

$(document).on('click', '.btn-update-subscriber', function(e) {
    e.preventDefault();

    $('.modal-update-subscriber').modal('show', $(e.target));
});

$(document).on('click', '.btn-remove-subscriber', function(e) {
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
                url: '/api/subscriber/remove/' + data_id,
                success: function(data) {
                    $('.subscriber-' + data_id).remove();
                },
                error: function(jqXhr, textStatus, errorThrown) {
                    processErrorFromAjax(jqXhr);
                }
            })
        }
    });
});

$('.modal-update-subscriber').on('show.bs.modal', function(e) {
    var button = $(e.relatedTarget);
    var data_id = button.data('id');

    console.log(data_id);

    var modal = $(this);

    if(data_id == undefined) {
        $.ajax({
            type: 'GET',
            url: '/api/field/list',
            headers: {
                'Authorization': 'Bearer ' + 
                    document.head.querySelector('meta[name="user-token"]').content
            },
            success: function(data) {
                var output = '';
                fields = data.fields;
                for(i = 0; i < fields.length; i++)  {
                    output += createFormInput(fields[i]);
                }

                modal.find('.modal-body').append(output);

            }
        });
    } else {
        $.ajax({
            type: 'GET',
            url: '/api/subscriber/show/' + data_id,
            headers: {
                'Authorization': 'Bearer ' + 
                    document.head.querySelector('meta[name="user-token"]').content
            },
            success: function(data) {
                var output = '<input type="hidden" name="id" value="' + data_id + '">';
                fields = data.fields;
                for(i = 0; i < fields.length; i++)  {
                    output += createFormInput(fields[i]);
                }

                modal.find('.modal-body').append(output);
            }
        });
    }
});

$('.modal-update-subscriber').on('hide.bs.modal', function() {
    $(this).find('.modal-body').html('');
});

$('.update-subscriber-form').on('submit', function(e) {
    e.preventDefault();

    var form = $(this);

    if(form.find('input[name="id"]').length <= 0) {
        $.ajax({
            type: 'POST',
            url: '/api/subscriber/create',
            headers: {
                'Authorization': 'Bearer ' + 
                    document.head.querySelector('meta[name="user-token"]').content
            },
            data: form.serialize(),
            success: function(data) {
                row = createSubscriberRow(data.subscriber);

                $(row).appendTo('.table-subscribers tbody');

                $('.modal-update-subscriber').modal('hide');
            },
            error: function(jqXhr, textStatus, errorThrown) {
                processErrorFromAjax(jqXhr);
            }
        });
    } else {
        var data_id = form.find('input[name="id"]').val();

        $.ajax({
            type: 'PUT',
            url: '/api/subscriber/update/' + data_id,
            headers: {
                'Authorization': 'Bearer ' + 
                    document.head.querySelector('meta[name="user-token"]').content
            },
            data: form.serialize(),
            success: function(data) {
                row = $('.subscriber-' + data_id);

                row.find('.subscriber-row-name').text(data.subscriber.name);
                row.find('.subscriber-row-email').text(data.subscriber.email);

                $('.modal-update-subscriber').modal('hide');
            },
            error: function(jqXhr, textStatus, errorThrown) {
                processErrorFromAjax(jqXhr);
            }
        });
    }
});
