//delete record
$(document).on('click', '.delete', function () {
    var slug = $(this).attr('data-slug');
    var delete_url = $(this).attr('data-del-url');
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: delete_url,
                type: 'DELETE',
                success: function (response) {
                    if (response) {
                        toastr.success('You have deleted record successfully.');
                        var oTable = $('.data_table').dataTable();
                        oTable.fnDraw(false);
                    } else {
                        toastr.error('Sorry something went wrong.');
                    }
                }
            });
        }
    })
});

$(document).ready(function () {
    $("form.submitBtnWithFileUpload").on('submit', function (e) {
        e.preventDefault();
        var thi = $(this);
        var actionName = $(this).attr('action');
        var dataMethod = $(this).attr('data-method');
        var modal_id = $(this).attr('data-modal-id');
        // Get the form data
        var formElement = $('#' + modal_id).find('#create-form');

        var formData = new FormData(formElement[0]);

        thi.find('.sub-btn').hide();
        thi.find('.loading-btn').show();

        $.ajax({
            type: 'post',
            url: actionName,
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function (response) {
                thi.find('.sub-btn').show();
                thi.find('.loading-btn').hide();
                if (response.success) {
                    toastr.success('You have added successfully.');
                    var oTable = $('.data_table').dataTable();
                    oTable.fnDraw(false);
                    $('#' + modal_id).modal('hide');
                    // Swal.fire(
                    //     'Done!',
                    //     'You have added record successfully.',
                    //     'success'
                    // )
                    showJqueryConfirm("Ticket has been created successfuly!", "green")
                    setTimeout(function () {
                        location.reload();
                    }, 1000); // 5000 milliseconds = 5 seconds

                } else if (response.error) {
                    // $('#' + modal_id).modal('hide');

                    toastr.error(response.error);
                } else if (response.error == false) {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                thi.find('.sub-btn').show();
                thi.find('.loading-btn').hide();
                // Parse the JSON response to get the error messages
                var errors = JSON.parse(xhr.responseText);
                // Reset the form errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
                $('.error').empty();

                // Loop through the errors and display them
                $.each(errors.errors, function (key, value) {
                    $('#' + key).addClass('is-invalid'); // Add the is-invalid class to the input element
                    $('#' + key + '_error').text(value[0]); // Add the error message to the error element
                });
            }
        });
    });
});

//submit
$(document).ready(function () {
    $('.submitBtn').click(function (e) {
        e.preventDefault(); // Prevent the form from submitting normally
        var thi = $(this);

        var url = $(this).closest('form').attr('action');
        var method = $(this).closest('form').attr('data-method');

        var formId = $(this).closest('form').attr('id');
        var modal_id = $(this).closest('form').attr('data-modal-id');

        // Get the form data
        var formData = $('#' + modal_id).find('#' + formId).serialize();

        // Check if the description variable exists in the serialized form data
        var fieldExists = formData.indexOf('description=') > -1;

        if (fieldExists) {
            //Get editor value.
            var editorData = CKEDITOR.instances.description.getData();
            // Combine the editor data with the serialized form data
            formData = formData + '&description=' + encodeURIComponent(editorData);
        }

        thi.parents('.action-btn').find('.sub-btn').hide();
        thi.parents('.action-btn').find('.loading-btn').show();

        // Send the AJAX request
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function (response) {
                // console.log(response)
                thi.parents('.action-btn').find('.sub-btn').show();
                thi.parents('.action-btn').find('.loading-btn').hide();
                if (response.success) {
                    if (response.message && response.message != "") {
                        toastr.success(response.message, 'Success', { timeOut: 1000 });
                    } else {
                        toastr.success('You have added successfully.', 'Success', { timeOut: 1000 });

                    }
                    var oTable = $('.data_table').dataTable();
                    oTable.fnDraw(false);

                    $('#' + modal_id).modal('hide');
                    $('#' + modal_id).removeClass('show');
                    $('#' + modal_id).parents('.card').find('.offcanvas-backdrop').removeClass('show');
                } else if (response.error) {
                    // $('#' + modal_id).modal('hide');
                    toastr.error(response.error);
                } else if (response.error == false) {
                    toastr.error(response.message);
                } else if (response.success == false) {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                thi.parents('.action-btn').find('.sub-btn').show();
                thi.parents('.action-btn').find('.loading-btn').hide();
                // Parse the JSON response to get the error messages
                var errors = JSON.parse(xhr.responseText);
                // Reset the form errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
                $('.error').empty();

                // Loop through the errors and display them
                $.each(errors.errors, function (key, value) {
                    $('#' + key).addClass('is-invalid'); // Add the is-invalid class to the input element
                    $('#' + key + '_error').text(value[0]); // Add the error message to the error element
                });
            }
        });
    });

    //apply discrepancy & leave and reload page
    $('.applyDiscrepancyLeaveBtn').click(function (e) {
        e.preventDefault(); // Prevent the form from submitting normally
        var thi = $(this);

        var url = $(this).closest('form').attr('action');
        var method = $(this).closest('form').attr('data-method');

        var formId = $(this).closest('form').attr('id');
        var modal_id = $(this).closest('form').attr('data-modal-id');

        // Get the form data
        var formData = $('#' + modal_id).find('#' + formId).serialize();

        // Check if the description variable exists in the serialized form data
        var fieldExists = formData.indexOf('description=') > -1;

        if (fieldExists) {
            //Get editor value.
            var editorData = CKEDITOR.instances.description.getData();
            // Combine the editor data with the serialized form data
            formData = formData + '&description=' + encodeURIComponent(editorData);
        }

        thi.parents('.action-btn').find('.sub-btn').hide();
        thi.parents('.action-btn').find('.loading-btn').show();

        // Send the AJAX request
        $.ajax({
            url: url,
            method: method,
            data: formData,
            success: function (response) {
                thi.parents('.action-btn').find('.sub-btn').show();
                thi.parents('.action-btn').find('.loading-btn').hide();
                if (response.success) {
                    toastr.success('You have added successfully.');
                    $('#' + modal_id).removeClass('show');
                    setTimeout(function () {
                        location.reload();
                    }, 1500); // 5000 milliseconds = 5 seconds
                } else if (response.error) {
                    $('#' + modal_id).modal('hide');

                    toastr.error(response.error);
                } else if (response.error == false) {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                thi.parents('.action-btn').find('.sub-btn').show();
                thi.parents('.action-btn').find('.loading-btn').hide();
                // Parse the JSON response to get the error messages
                var errors = JSON.parse(xhr.responseText);
                // Reset the form errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').empty();
                $('.error').empty();

                // Loop through the errors and display them
                $.each(errors.errors, function (key, value) {
                    thi.closest('form').find('#' + key).addClass('is-invalid'); // Add the is-invalid class to the input element
                    thi.closest('form').find('#' + key + '_error').text(value[0]); // Add the error message to the error element
                });
            }
        });
    });
});

//Open modal for adding
$('#add-btn, .add-btn').on('click', function () {
    var targeted_modal = $(this).attr('data-bs-target');

    //reset
    $(targeted_modal).find('#create-form input[type="text"], #create-form textarea').val('');
    $(targeted_modal).find('#create-form input[type="number"]').val('');
    $(targeted_modal).find('#create-form input[type="date"]').val('');
    $(targeted_modal).find('#create-form input[type="email"]').val('');
    $(targeted_modal).find('#create-form input[type="time"]').val('');
    $(targeted_modal).find('#create-form select').val('');
    $(targeted_modal).find('#create-form input[type="checkbox"], #create-form input[type="radio"]').prop('checked', false);
    $(targeted_modal).find('#attachment-file').html('');

    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.description) {
        CKEDITOR.instances.description.setData('');
    }
    //reset

    var url = $(this).attr('data-url');
    var modal_label = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(modal_label);
    $(targeted_modal).find("#create-form").attr("action", url);
    $(targeted_modal).find("#create-form").attr("data-method", 'POST');

    // $('select').select2();
    $('select').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent(),
        });
    });
});

//Open modal for editing
$(document).on('click', '.edit-btn', function () {
    var targeted_modal = $(this).attr('data-bs-target');

    var url = $(this).attr('data-url');
    var modal_label = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(modal_label);
    $(targeted_modal).find("#create-form").attr("action", url);
    $(targeted_modal).find("#create-form").attr("data-method", 'PUT');

    var edit_url = $(this).attr('data-edit-url');

    $.ajax({
        url: edit_url,
        method: 'GET',
        beforeSend: function () {
            $(targeted_modal).find('#edit-content').empty()
        },
        success: function (response) {
            $(targeted_modal).find('#edit-content').html(response);
            // $('.select2').select2({
            //     dropdownParent: $(targeted_modal)
            // });
            // if ($('select').data('select2')) {
            //     $('select').select2('destroy');
            // }
            // $('select').select2();
        }
    });
});

$(document).on('click', '.impersonate-btn', function () {
    var url = $(this).attr('data-url');
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to visit!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, visit it!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = url;
        }
    });
});

$(document).on('click', '.approve-btn', function () {
    var status_url = $(this).attr('data-status-url');
    var parentModalBody = $(this).closest('.input-checkbox'); // Find the parent modal body

    var modal_id = $(this).attr('data-modal-id');

    // Get all checked checkboxes within the parent modal body
    var checkedValues = [];
    parentModalBody.find(".checkbox:checked").each(function () {
        var id = $(this).val();
        var type = $(this).attr("data-type");
        checkedValues.push({ id: id, type: type });
    });

    var encodedJson = JSON.stringify(checkedValues);

    $('#' + modal_id).modal('hide');

    Swal.fire({
        title: 'Are you sure?',
        text: "You want to change status!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: status_url,
                type: 'get',
                data: { data: encodedJson },
                success: function (response) {
                    if (response) {
                        toastr.success('You have saved changes successfully.');
                        // var oTable = $('.data_table').dataTable();
                        // oTable.fnDraw(false);
                        setTimeout(function () {
                            location.reload();
                        }, 1500);
                    } else {
                        toastr.error('Sorry something went wrong.');
                    }
                }
            });
        }
    });
});

$(document).on('click', '.bluk-approve-btn', function () {
    var status_url = $(this).attr('data-url');
    var parentModalBody = $(this).closest('.input-checkbox'); // Find the parent modal body
    $(this).attr('disabled', true);

    // Get all checked checkboxes within the parent modal body
    var checkedValues = [];
    parentModalBody.find(".checkbox:checked").each(function () {
        var id = $(this).val();
        checkedValues.push({ id: id });
    });

    var encodedJson = JSON.stringify(checkedValues);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: status_url,
        type: 'get',
        data: { data: encodedJson },
        success: function (response) {
            if (response == 'true') {
                toastr.success('You have added successfully.');
                // setTimeout(function () {
                //     location.reload();
                // }, 1500); // 5000 milliseconds = 5 seconds
                var oTable = $('.data_table').dataTable();
                oTable.fnDraw(false);
            } else {
                toastr.error('Something went wrong.');
            }
        }
    });
});

$(document).on('click', '.bluk-delete-btn', function () {
    var status_url = $(this).attr('data-url');
    var parentModalBody = $(this).closest('.input-checkbox'); // Find the parent modal body
    $(this).attr('disabled', true);

    // Get all checked checkboxes within the parent modal body
    var checkedValues = [];
    parentModalBody.find(".checkbox:checked").each(function () {
        var id = $(this).val();
        checkedValues.push({ id: id });
    });

    var encodedJson = JSON.stringify(checkedValues);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: status_url,
        type: 'get',
        data: { data: encodedJson },
        success: function (response) {
            if (response == 'true') {
                toastr.success('You have added successfully.', 'Success', { timeOut: 1000 });
                var oTable = $('.data_table').dataTable();
                oTable.fnDraw(false);
            } else {
                toastr.error('Something went wrong.');
            }
        }
    });
});

$(document).on('click', '.view-modal-btn', function () {
    var targeted_modal = $(this).attr('data-bs-target');
    var title = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(title);
    var html = '<tr>' +
        '<td colspan="999">' +
        '<div class="d-flex justify-content-center align-items-center" style="height: 20vw;>' +
        '<div class="demo-inline-spacing">' +
        '<div class="spinner-border spinner-border-lg text-primary" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</td>' +
        '</tr>';
    $(targeted_modal).find('#show-content').html(html);
    var get_url = $(this).attr('data-show-url');
    $.ajax({
        url: get_url,
        method: 'GET',
        success: function (response) {
            $(targeted_modal).find('#show-content').html(response);
        }
    });
});

$(document).on('click', '.show', function () {
    var targeted_modal = $(this).attr('data-bs-target');
    var modal_label = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(modal_label);
    var html = '<div class="d-block w-100">' +
        '<div class="d-block w-100">' +
        '<div class="d-flex justify-content-center align-items-center" style="height: 20vw;>' +
        '<div class="demo-inline-spacing">' +
        '<div class="spinner-border spinner-border-lg text-primary" role="status">' +
        '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>';
    $(targeted_modal).find('#show-content').html(html);
    var show_url = $(this).attr('data-show-url');
    $.ajax({
        url: show_url,
        method: 'GET',
        success: function (response) {
            $(targeted_modal).find('#show-content').html(response);
        }
    });
});

$(document).on('click', '.attendance-filter-btn', function () {
    var filter_date = $('#flatpickr-range').val();
    var filter_behavior = $('#filter_behavior').val();
    var employees = $('#employees_ids').val();
    var departments = $('#department_ids').val();

    departments = JSON.stringify(departments);
    employees = JSON.stringify(employees);

    if (employees != '') {
        $('#process').removeClass('d-none');
        $(this).addClass('d-none');
    } else {
        $('#process').addClass('d-none');
        $(this).removeClass('d-none');
    }

    var current = $(this);

    var show_url = $(this).attr('data-show-url');
    $.ajax({
        url: show_url,
        method: 'GET',
        data: { 'filter_date': filter_date, 'filter_behavior': filter_behavior, 'employees': employees, 'departments': departments },
        success: function (response) {
            $('#process').addClass('d-none');
            current.removeClass('d-none');
            $('#show-filter-attendance-content').html(response);
        }
    });
});

$(document).on('click', '.monthly-attendance-filter-report-btn', function () {
    var employees = $('#employees_ids').val();
    var year = $('#getYear').val();
    var month = $('#getMonth').val();

    employees = JSON.stringify(employees);

    if (employees != '') {
        $('#process').removeClass('d-none');
        $(this).addClass('d-none');
    } else {
        $('#process').addClass('d-none');
        $(this).removeClass('d-none');
    }

    var current = $(this);

    var show_url = $(this).attr('data-show-url');
    $.ajax({
        url: show_url,
        method: 'GET',
        data: { 'employees': employees, 'year': year, 'month': month },
        success: function (response) {
            $('#process').addClass('d-none');
            current.removeClass('d-none');
            $('#show-filter-attendance-content').html(response);
        }
    });
});

$(document).on('click', '.remove-image-btn', function () {
    var id = $(this).attr('data-id');
    var remove_url = $(this).attr('data-remove-url');

    $.ajax({
        url: remove_url,
        type: 'GET',
        success: function (response) {
            if (response) {
                $('#id-' + id).remove();
            } else {
                Swal.fire(
                    'Not Deleted!',
                    'Sorry! Something went wrong.',
                    'danger'
                )
            }
        }
    });
});

$(document).on('click', '.attendance-mark-btn', function () {
    var url = $(this).attr('data-url');
    var attendance_id = $(this).attr('data-attendance-id');
    var mark_type = $(this).attr('data-mark-type');
    var user_id = $(this).attr('data-user');

    Swal.fire({
        title: 'Are you sure?',
        text: 'Do you want to mark it.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: 'POST',
                data: { attendance_id: attendance_id, mark_type: mark_type, user_id: user_id },
                success: function (response) {
                    if (response) {
                        Swal.fire(
                            'Done!',
                            'You have marked successfully.',
                            'success'
                        )
                        setTimeout(function () {
                            location.reload();
                        }, 2000); // 5000 milliseconds = 5 seconds
                    } else {
                        Swal.fire(
                            'Alert!',
                            'Sorry! Something went wrong.',
                            'danger'
                        )
                    }
                }
            });
        }
    });
});
$(document).on('click', '.status-btn', function () {
    var status_url = $(this).attr('data-status-url');
    // Swal.fire({
    //     title: 'Are you sure?',
    //     text: "You want to change status!",
    //     icon: 'warning',
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Yes, change it!'
    // }).then((result) => {
    //     if (result.isConfirmed) {
    //         $.ajax({
    //             url: status_url,
    //             type: 'get',
    //             success: function (response) {
    //                 if (response) {
    //                     toastr.success('You have changed status successfully.');
    //                     var oTable = $('.data_table').dataTable();
    //                     oTable.fnDraw(false);
    //                 } else {
    //                     toastr.error('Sorry! Something went wrong.');
    //                 }
    //             }
    //         });
    //     }
    // });
    var theme = getThemeAccordingToAppMode();
    $.confirm({
        title: 'Are You Sure?',
        content: "You won't be able to revert this!",
        theme: theme,
        type: 'orange',
        // typeAnimated: true,
        draggable: true,
        // animation : "rotatey",
        buttons: {
            confirm: function () {
                $.ajax({
                    url: status_url,
                    type: 'get',
                    success: function (response) {
                        if (response) {
                            showJqueryConfirm("You have changed status successfully.", "green")
                            toastr.success('You have changed status successfully.');
                            var oTable = $('.data_table').dataTable();
                            oTable.fnDraw(false);
                        } else {
                            showJqueryConfirm("Sorry! Something went wrong! ", "red")
                            toastr.error('Sorry! Something went wrong.');
                        }
                    }
                });

            },
            cancel: function () {
                // showJqueryConfirm("You have cancelled." , "orange" , "material")
            },

        }
    });



});
$(document).on('click', '.with-comment-status-btn', function () {
    var status_url = $(this).attr('data-status-url');
    var status_type = $(this).attr('data-status-type');
    Swal.fire({
        title: 'Are you sure?',
        html: 'You want to change status!<br /><br /> Add Comment ( Optional ):',
        icon: 'warning',
        input: 'textarea',
        inputPlaceholder: 'Enter your comment here...',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
    }).then((result) => {
        if (result.isConfirmed) {
            const comment = result.value;

            $.ajax({
                url: status_url,
                type: 'get',
                data: { status_type: status_type, comment: comment },
                success: function (response) {
                    if (response) {
                        toastr.success('You have changed status successfully.');
                        var oTable = $('.data_table').dataTable();
                        oTable.fnDraw(false);
                    } else {
                        toastr.error('Sorry! Something went wrong.');
                    }
                }
            });
        }

    });
});