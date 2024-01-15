$(document).on('click', '.submitBtn .insuranceBtn', function(){
    event.preventDefault();
    
    var relationship = $('.relationships').val();
    if(relationship=='father'){
       $('#father_cnic_number_error').html('Father cnic number is required.!'); 
       return false;
    }else{
        return true;
    }
});

$(document).on('change', '#marital_status, #sex', function(){
    var marital_status = $('#marital_status').val();
    var sex = $('#sex').val();
    var html = '';
    if(marital_status==1 && sex==1){
        html = '<option value="" selected>Select relation</option>'+
                '<option value="wife">Wife</option>'+
                '<option value="son">Son</option>'+
                '<option value="daughter">Daughter</option>';
    }else if(marital_status==1 && sex==0){
        html = '<option value="" selected>Select relation</option>'+
                '<option value="husband">Husband</option>'+
                '<option value="son">Son</option>'+
                '<option value="daughter">Daughter</option>';
    }else if(marital_status != ''){
        html = '<option value="" selected>Select relation</option>'+
                '<option value="father">Father</option>'+
                '<option value="mother">Mother</option>';
    }
    
    $('.relationships').html(html);
});
$(document).on('change', '.relationships', function(){
    var relation = $(this).val();
    if(relation=='father'){
        var html = '';
        html =  '<label class="form-label" for="father_cnic_number">CNIC <span class="text-danger">*</span></label>'+
                '<input type="text" id="father_cnic_number" name="father_cnic_number" class="form-control cnic_number" required placeholder="Enter father cnic number "/>'+
                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                '<span id="father_cnic_number_error" class="text-danger error"></span>';
        $(this).parents('.relation_data').find('.cnic').html(html);
    }else{
        $(this).parents('.relation_data').find('.cnic').html('');
    }
});
$(document).on('click', '.add-more-btn', function(){
    var marital_status = $('#marital_status').val();
    var rel_html = '';
    if(marital_status==1){
        rel_html = '<option value="" selected>Select relation</option>'+
                '<option value="husband">Husband</option>'+
                '<option value="wife">Wife</option>'+
                '<option value="son">Son</option>'+
                '<option value="daughter">Daughter</option>';
    }else{
        rel_html = '<option value="" selected>Select relation</option>'+
                '<option value="father">Father</option>'+
                '<option value="mother">Mother</option>';
    }

    var html = '';
    html = '<span class="relation_data">'+
                '<div class="row mt-4 w-full border-top py-2 position-relative">'+
                    '<div class="close-btn-wrapper position-absolute d-flex flex-row-reverse mb-3">'+
                        '<button type="button" class="btn btn-label-primary btn-sm btn-relation-close"><i class="fa fa-close icon-close"></i></button>'+
                    '</div>' +
                    '<div class="col-md-6">'+
                        '<label class="form-label" for="relationships">Relationship </label>'+
                        '<select class="form-control relationships" id="relationships" name="relationships[]">'+
                            rel_html +
                        '</select>'+
                        '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                        '<span id="relationships_error" class="text-danger error"></span>'+
                    '</div>'+
                    '<div class="col-md-6">'+
                        '<label class="form-label" for="family_rel_names">Name </label>'+
                        '<input type="text" id="family_rel_names" name="family_rel_names[]" class="form-control" placeholder="Enter name" />'+
                        '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                        '<span id="family_rel_names_error" class="text-danger error"></span>'+
                    '</div>'+
                '</div>'+
                '<div class="row mt-3">'+
                    '<div class="col-md-6">'+
                        '<label class="form-label" for="family_rel_dobs">Date of birth </label>'+
                        '<input type="date" id="family_rel_dobs" name="family_rel_dobs[]" class="form-control" />'+
                        '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                        '<span id="family_rel_dobs_error" class="text-danger error"></span>'+
                    '</div>'+
                    '<div class="col-md-6 cnic"></div>'+
                '</div>'+
            '</span>';

            $('#add-more-data').append(html);
});

$(document).on('click', '.btn-relation-close', function(){
    $(this).parents('.relation_data').remove();
});

$('#name_as_per_cnic').on('keyup', function() {
    // Get the input value
    var inputValue = $(this).val();
    
    // Define a regular expression pattern to check for letters only
    var letterPattern = inputValue.replace(/[0-9]/g, '');
    
    if (!letterPattern.test(inputValue)) {
        // Display an error message if the input doesn't match the pattern
        $('#name_as_per_cnic_error').text('Please enter only letters (no numbers)');
    } else {
        // Clear the error message if the input is valid
        $('#name_as_per_cnic_error').text('');
    }
});
$(document).on('keyup', '#father_cnic_number', function() {
    // Get the input value
    var cnic = $(this).val();
    
    if (cnic.length > 1 && cnic.length < 15) {
        // Display an error message if the input doesn't match the pattern
        $('#father_cnic_number_error').text('Father cnic length is not correct.');
    } else {
        // Clear the error message if the input is valid
        $('#father_cnic_number_error').text('');
    }
});
$(document).on('keyup', '#cnic_number', function() {
    var cnic_number = $(this).val();
    
    if (cnic_number.length > 1 && cnic_number.length < 15) {
        $('#cnic_number_error').text('CNIC length is not correct.');
    } else {
        $('#cnic_number_error').text('');
    }
});
$(document).ready(function(){
    $("#edit_insurance").submit(function (event) {
        event.preventDefault();
        var cnic = $('#father_cnic_number').val();
        var cnic_number = $('#cnic_number').val();
    
        var isValid = true;
    
        if(cnic_number.length == ''){
            $('#cnic_number_error').text('CNIC number is required.');
        }else if (cnic_number.length > 1 && cnic_number.length < 15) {
            $('#cnic_number_error').text('CNIC number length is not correct.');
            isValid = false;
        } else {
            $('#cnic_number_error').text('');
        }
        // if (cnic.length > 1 && cnic.length < 15) {
        //     $('#father_cnic_number_error').text('Father CNIC number length is not correct.');
        //     isValid = false;
        // } else {
        //     $('#father_cnic_number_error').text('');
        // }
        if (isValid) {
            this.submit(); // Submit the form
        }
    });
});