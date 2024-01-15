$(document).on('click', '.late-in-box', function() {
    var targeted_modal = $(this).attr('data-bs-target');
    var user_id = $(this).data('user-id');
    var data = $(this).data('latein');

    // data.sort((a, b) => a.type.localeCompare(b.type));

    // if (user_status == false) {
    //     $(targeted_modal).find('button').remove();
    //     $(targeted_modal).find('.select-all').attr('disabled', true);
    // }

    var html = '';
    $.each(data, function(index, value) {
        var status = '';
        var behavior = '<span class="badge bg-label-danger">Not Applied</span>';
        var applied = '';
        var applied_class = '';

        if (value.status === 0) {
            status = '<span class="badge bg-label-warning">Pending</span>';
        } else if (value.status == 1) {
            status = '<span class="badge bg-label-success">Approved</span>';
        } else if (value.status == 2) {
            status = '<span class="badge bg-label-danger">Rejected<s/pan>';
        } else {
            status = '-';
        }

        if (value.status !== '') {
            behavior = '<span class="badge bg-label-success">Applied</span>';
            applied = 'disabled checked';
            applied_class = 'class="form-check-input"';
        } else {
            // if (user_status == false) {
            //     applied_class = 'disabled class="form-check-input checkbox"';
            // } else {
            applied_class = 'class="form-check-input checkbox"';
            // }
        }

        html += '<tr>' +
            '<td>' +
            '<div>' +
            '<input ' + applied_class + applied + ' data-type="' + value.type + '" data-user-id="' + user_id + '" type="checkbox" value="' + value.date + '" />' +
            '</div>' +
            '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + value.time + '</td>' +
            '<td><span class="badge bg-label-danger me-1">Late In ' + value.label + '</span></td>' +
            '<td>' + behavior + '</td>' +
            '<td>' + status + '</td>' +
            '</tr>';

    });

    $('#late-in-summary').html(html);
});

$(document).on('click', '.early-out-box', function() {
    var targeted_modal = $(this).attr('data-bs-target');
    var user_id = $(this).data('user-id');
    var data = $(this).attr('data-earlyOut');
    data = JSON.parse(data);

    // if (user_status == false) {
    //     $(targeted_modal).find('button').remove();
    //     $(targeted_modal).find('.select-all').attr('disabled', true);
    // }

    var html = '';
    $.each(data, function(index, value) {
        var status = '';
        var behavior = '<span class="badge bg-label-danger">Not Applied</span>';
        var applied = '';
        var applied_class = '';

        if (value.status === 0) {
            status = '<span class="badge bg-label-warning">Pending</span>';
        } else if (value.status == 1) {
            status = '<span class="badge bg-label-success">Approved</span>';
        } else if (value.status == 2) {
            status = '<span class="badge bg-label-danger">Rejected<s/pan>';
        } else {
            status = '-';
        }

        if (value.status !== '') {
            behavior = '<span class="badge bg-label-success">Applied</span>';
            applied = 'disabled checked';
            applied_class = 'class="form-check-input"';
        } else {
            // if (user_status == false) {
            // applied_class = 'disabled class="form-check-input checkbox"';
            // } else {
            applied_class = 'class="form-check-input checkbox"';
            // }
        }

        html += '<tr>' +
            '<td>' +
            '<div>' +
            '<input ' + applied_class + applied + ' data-type="' + value.type + '" data-user-id="' + user_id + '" type="checkbox" value="' + value.date + '" />' +
            '</div>' +
            '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + value.time + '</td>' +
            '<td><span class="badge bg-label-danger me-1">Early Out</span></td>' +
            '<td>' + behavior + '</td>' +
            '<td>' + status + '</td>' +
            '</tr>';

    });

    $('#early-out-summary').html(html);
});

$(document).on('click', '.half-day-box', function() {
    var targeted_modal = $(this).attr('data-bs-target');
    var user_id = $(this).data('user-id');
    var user_remaining_leave_status = $(this).data('remaining-leaves');
    var data = $(this).data('halfday');

    if (user_remaining_leave_status == false) {
        $(targeted_modal).find('button').remove();
        $(targeted_modal).find('.select-all').attr('disabled', true);
    }

    // if (user_status == false) {
    //     $(targeted_modal).find('button').remove();
    //     $(targeted_modal).find('.select-all').attr('disabled', true);
    // }

    var html = '';
    $.each(data, function(index, value) {
        var status = '';
        var behavior = '<span class="badge bg-label-danger">Not Applied</span>';
        var applied = '';
        var applied_class = '';

        console.log(value);

        if (value.status === 0) {
            status = '<span class="badge bg-label-warning">Pending</span>';
        } else if (value.status == 1) {
            status = '<span class="badge bg-label-success">Approved</span>';
        } else if (value.status == 2) {
            status = '<span class="badge bg-label-danger">Rejected</span>';
        } else {
            status = '-';
        }

        if (value.status !== '') {
            behavior = '<span class="badge bg-label-success">Applied</span>';
            applied = 'disabled checked';
            applied_class = 'class="form-check-input"';
        } else {
            if (user_remaining_leave_status == false) {
                applied_class = 'disabled class="form-check-input checkbox"';
            } else {
                applied_class = 'class="form-check-input checkbox"';
            }
        }
        var type = '';
        if (value.type == 'lasthalf') {
            type = '<span class="badge bg-label-warning me-1">Last Half</span>';
        } else {
            if (value.label != '') {
                type = '<span class="badge bg-label-info me-1">Marked as Half Day</span>';
            } else {
                type = '<span class="badge bg-label-info me-1">First Half</span>';
            }
        }

        html += '<tr>' +
            '<td>' +
            '<div>' +
            '<input ' + applied_class + applied + ' data-type="' + value.type + '" data-user-id="' + user_id + '" type="checkbox" value="' + value.date + '" />' +
            '</div>' +
            '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + value.time + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + behavior + '</td>' +
            '<td>' + status + '</td>' +
            '</tr>';

    });

    $('#half-days-summary').html(html);
});

$(document).on('click', '.absent-dates-box', function() {
    var targeted_modal = $(this).attr('data-bs-target');
    var user_remaining_leave_status = $(this).data('remaining-leaves');
    var user_id = $(this).data('user-id');
    var data = $(this).data('absent');

    if (user_remaining_leave_status == false) {
        $(targeted_modal).find('button').remove();
        $(targeted_modal).find('.select-all').attr('disabled', true);
    }

    // if (user_status == false) {
    //     $(targeted_modal).find('button').remove();
    //     $(targeted_modal).find('.select-all').attr('disabled', true);
    // }

    var html = '';
    $.each(data, function(index, value) {
        var status = '';
        var behavior = '<span class="badge bg-label-danger">Not Applied</span>';
        var applied = '';
        var applied_class = '';

        if (value.status === 0) {
            status = '<span class="badge bg-label-warning">Pending</span>';
        } else if (value.status == 1) {
            status = '<span class="badge bg-label-success">Approved</span>';
        } else if (value.status == 2) {
            status = '<span class="badge bg-label-danger">Rejected</span>';
        } else {
            status = '-';
        }

        if (value.status !== '') {
            behavior = '<span class="badge bg-label-success">Applied</span>';
            applied = 'disabled checked';
            applied_class = 'class="form-check-input"';
        } else {
            if (user_remaining_leave_status == false) {
                applied_class = 'disabled class="form-check-input checkbox"';
            } else {
                applied_class = 'class="form-check-input checkbox"';
            }
        }

        var type = '<span class="badge bg-label-danger me-1">Absent</span>';

        html += '<tr>' +
            '<td>' +
            '<div>' +
            '<input ' + applied_class + applied + ' data-type="' + value.type + '" data-user-id="' + user_id + '" type="checkbox" value="' + value.date + '" />' +
            '</div>' +
            '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + value.label + '</td>' +
            '<td>' + behavior + '</td>' +
            '<td>' + status + '</td>' +
            '</tr>';

    });

    $('#team-absent-modal-content-body').html(html);
});

$(document).ready(function() {
    // Event handler for the "Select All" checkbox
    $(document).on('click', '.select-all', function() {
        // Check/uncheck all checkboxes based on the Select All checkbox
        $(this).parents('.input-checkbox').find(".checkbox").prop("checked", $(this).prop("checked"));

        // var anyCheckboxChecked = $('.input-checkbox .checkbox:not(selectAll):checked').length > 0;
        var total_checked_length = $(this).parents('.input-checkbox').find(".checkbox:checked").length;

        if (total_checked_length > 0) {
            // Enable/disable the button based on the Select All checkbox
            $(this).parents('.input-checkbox').find(".apply-btn").prop("disabled", !$(this).prop("checked"));
            $(this).parents('.input-checkbox').find('.approve-btn').prop('disabled', !$(this).prop('checked'));
        } else {
            $(this).parents('.input-checkbox').find(".apply-btn").prop("disabled", true);
            $(this).parents('.input-checkbox').find('.approve-btn').prop('disabled', true);
        }
    });

    // Individual checkbox click event
    $(document).on('click', ".checkbox", function() {
        // Check the Select All checkbox if all checkboxes are checked
        var total_checkboxes_length = $(this).parents('.input-checkbox').find(".checkbox").length;
        var total_checked_length = $(this).parents('.input-checkbox').find(".checkbox:checked").length;

        if (total_checked_length > 0 && total_checked_length < total_checkboxes_length) {
            $(this).parents('.input-checkbox').find(".select-all").prop("checked", false);
            $(this).parents('.input-checkbox').find(".apply-btn").prop("disabled", false);
            $(this).parents('.input-checkbox').find(".approve-btn").prop("disabled", false);
        } else if (total_checked_length === total_checkboxes_length) {
            $(this).parents('.input-checkbox').find(".select-all").prop("checked", true);
            $(this).parents('.input-checkbox').find(".apply-btn").prop("disabled", !$(this).prop("checked"));
            $(this).parents('.input-checkbox').find(".approve-btn").prop("disabled", !$(this).prop("checked"));
        } else {
            $(this).parents('.input-checkbox').find(".select-all").prop("checked", false);
            $(this).parents('.input-checkbox').find(".apply-btn").prop("disabled", true);
            $(this).parents('.input-checkbox').find(".approve-btn").prop("disabled", true);
        }
    });

    $(document).on('click', '.apply-btn', function() {
        var parentModalBody = $(this).closest('.input-checkbox'); // Find the parent modal body

        var url = $(this).attr('data-url');
        var modal_id = $(this).attr('data-modal-id');

        $('#view-reason-modal').find("#create-form").attr("action", url);

        // Get all checked checkboxes within the parent modal body
        var checkedValues = [];
        parentModalBody.find(".checkbox:checked").each(function() {
            var value = $(this).val();
            var type = $(this).attr("data-type");
            var user_id = $(this).attr("data-user-id");
            checkedValues.push({ user_id: user_id, date: value, type: type });
        });

        var encodedJson = JSON.stringify(checkedValues);
        $('#applied_dates').val(encodedJson);

        $('#' + modal_id).modal('hide');
        $('#view-reason-modal').modal('show');
    });
});

$(document).on('click', '.admin-late-in-box', function() {
    var data = $(this).data('latein');
    var html = '';
    var counter = 1;
    $.each(data, function(index, value) {
        var type = value.type;
        if (type == 'earlyout') {
            type = '<span class="badge bg-label-warning">Early Out</span>';
        } else {
            type = '<span class="badge bg-label-danger">Late In</span>';
        }
        html += '<tr>' +
            '<td>' + counter++ + '</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + value.punchIn + '</td>' +
            '<td>' + value.punchOut + '</td>' +
            '</tr>';
    });
    $('#admin-late-in-summary').html(html);
});

$(document).on('click', '.admin-half-day-box', function() {
    var data = $(this).data('halfday');
    // Sort the 'data' array in descending order based on the 'type' field

    data.sort((a, b) => a.type.localeCompare(b.type));

    // Initialize 'html' and 'counter' before the loop
    let html = '';
    let counter = 0;
    $.each(data, function(index, value) {
        // var type = '<span class="badge bg-label-half-day">Half Day</span>';
        let type = '';
        if (value.type == 'firsthalf') {
            type = '<span class="badge bg-label-info">First Half</span>';
        } else {
            type = '<span class="badge bg-label-warning">Last Half</span>';
        }

        html += '<tr>' +
            '<td>' +
            '<div>' + ++counter + '.</div>' +
            '</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + value.punchIn + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + value.punchOut + '</td>' +
            '</tr>';
    });

    $('#admin-half-days-summary').html(html);
});

$(document).on('click', '.admin-absent-dates-box', function() {
    var data = $(this).data('absent');

    var html = '';
    var counter = 1;
    $.each(data, function(index, value) {
        var type = '<span class="badge bg-label-danger">Absent</span>';
        html += '<tr>' +
            '<td>' + counter++ + '</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + '</td>' +
            '</tr>';
    });

    $('#admin-team-absent-modal-content-body').html(html);
});

$(document).on('click', '.admin-team-summary-box', function() {
    var data = $(this).data('today-summary');

    var targeted_modal = $(this).attr('data-bs-target');
    var title = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(title);

    var html = '';
    var counter = 1;
    $.each(data, function(index, value) {
        var type = value.type;
        if (type == 'firsthalf' || type == 'lasthalf') {
            type = '<span class="badge bg-label-half-day">Half Day</span>';
        } else if (type == 'absent') {
            type = '<span class="badge bg-label-full-day">Absent</span>';
        } else if (type == 'earlyout') {
            type = '<span class="badge bg-label-late-in">Early Out</span>';
        } else if (type == 'lateIn') {
            type = '<span class="badge bg-label-late-in">Late In</span>';
        } else {
            type = '<span class="badge bg-label-regular">Regular</span>';
        }

        var punch = '-';
        if (value.punchIn != undefined) {
            punch = value.punchIn;
        }
        if (value.punchOut != undefined) {
            punchOut = value.punchOut;
        } else {
            punchOut = '-';
        }
        html += '<tr>' +
            '<td>' + counter++ + '.</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + punch + '</td>' +
            '<td>' + punchOut + '</td>' +
            '</tr>';
    });

    $('#admin-team-summary').html(html);
});