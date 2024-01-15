//Open modal for adding
$(document).on('click', '#terminate', function() {
    var user_id = $(this).attr('data-user-id');
    var targeted_modal = $(this).attr('data-bs-target');

    //reset
    $(targeted_modal).find('#create-form input[type="text"], #create-form textarea').val('');
    $(targeted_modal).find('#create-form input[type="number"]').val('');
    $(targeted_modal).find('#create-form input[type="date"]').val('');
    $(targeted_modal).find('#create-form input[type="email"]').val('');
    $(targeted_modal).find('#create-form input[type="time"]').val('');
    $(targeted_modal).find('#create-form select').val('');
    $(targeted_modal).find('#create-form input[type="checkbox"], #create-form input[type="radio"]').prop('checked', false);

    if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.description) {
        CKEDITOR.instances.description.setData('');
    }
    //reset

    var url = $(this).attr('data-url');
    var modal_label = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(modal_label);
    $(targeted_modal).find('#create-form').find('#terminate_user_id').val(user_id);
    $(targeted_modal).find("#create-form").attr("action", url);
    $(targeted_modal).find("#create-form").attr("data-method", 'POST');
    if ($('select').data('select2')) {
        $('select').select2('destroy');
    }
    $('select').select2();
});

//Status
$(document).on('click', '.emp-status-btn', function() {
    var status_url = $(this).attr('data-status-url');
    var status_type = $(this).attr('data-status-type');
    $title = "Do you want to change change status?";
    if (status_type == 'terminate') {
        $title = 'Do you want to terminate?'
    } else if (status_type == 'remove') {
        $title = 'Do you want to remove from employee list?'
    }
    Swal.fire({
        title: 'Are you sure?',
        text: $title,
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
                type: 'POST',
                data: { status_type: status_type },
                success: function(response) {
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

//add salary
$(document).on('click', '.promote-employee-btn', function() {
    var url = $(this).attr('data-url');
    var get_promote_url = $(this).attr('data-edit-url');
    var user_id = $(this).attr('data-user-id');
    var title = $(this).attr('data-title');
    
    $('#salary-title-label').html(title);
    $("#promote-employee-form").attr("action", url);
    $("#promote-employee-form").attr("data-method", 'POST');
    
    $.ajax({
        url: get_promote_url,
        type: 'GET',
        data: { user_id: user_id },
        success: function(response) {
            $('#promote-content').html(response);
            $('#promote-employee-modal').modal('show');
        }
    });
});
$('.form-select').select2();