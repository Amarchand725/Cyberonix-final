function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

$(document).on('click', '.prevQuestionShow', function() {
    var step = $(this).attr('data-step');
    $('section.steps').hide();
    $('section.' + step).show();
});

$(document).on('click', '.nextQuestionShow', function() {
    var validationChecker = false;
    var step = $(this).attr('data-step');
    var currentStep = $(this).attr('data-current-step');

    if (currentStep == 'step5') {
        var manager_id = $('form.employeee #manager_id').val();

        if (manager_id == '') {
            $('form.employeee #manager_id').addClass('invalid');
        }

        if (manager_id != '') {
            validationChecker = true;
        }
    }
    if (currentStep == 'step1') {
        var name = $('form.employeee #name').val();
        var fathername = $('form.employeee #father-name').val();
        var useremail = $('form.employeee #user-email').val();
        var birthdate = $('form.employeee #birth-date').val();
        var birthdateCheck = $('form.employeee #birth-date').attr('date-check');
        var cnic_no = $('form.employeee #CNIC_No').val();
        var contactno = $('form.employeee #contact-no').val();
        var emergencyno = $('form.employeee #emergency-no').val();
        var address = $('form.employeee #address').val();
        var apartment = $('form.employeee #apartment').val();
        var status = $('form.employeee #status').val();
        if (name == '') {
            $('form.employeee #name').addClass('invalid');
        }
        if (fathername == '') {
            $('form.employeee #father-name').addClass('invalid');
        }
        if (address == '') {
            $('form.employeee #address').addClass('invalid');
        }
        if (apartment == '') {
            $('form.employeee #apartment').addClass('invalid');
        }
        if (status == '') {
            $('form.employeee #status').addClass('invalid');
        }
        if (!(new Date(birthdate) <= new Date(birthdateCheck))) {
            $('form.employeee #birth-date').addClass('invalid');
        }
        if (useremail == '' || !isEmail(useremail)) {
            $('form.employeee #user-email').addClass('invalid');
        }
        if (cnic_no == '' || cnic_no.indexOf('_') !== -1) {
            $('form.employeee #CNIC_No').addClass('invalid');
        }
        if (contactno == '' || contactno.indexOf('_') !== -1) {
            $('form.employeee #contact-no').addClass('invalid');
        }
        if (emergencyno == '' || emergencyno.indexOf('_') !== -1) {
            $('form.employeee #emergency-no').addClass('invalid');
        }
        if (name != '' && fathername != '' && address != '' && apartment != '' && status != '' && new Date(birthdate) <= new Date(birthdateCheck) && useremail != '' && isEmail(useremail) &&
            cnic_no != '' && cnic_no.indexOf('_') == -1 && contactno != '' && contactno.indexOf('_') == -1 && emergencyno != '' && emergencyno.indexOf('_') == -1) {
            validationChecker = true;
        }
    }
    if (currentStep == 'step2') {
        var position = $('form.employeee #applied_for_position').val();
        var expectedSalary = $('form.employeee #expected-salary').val();
        var joiningDate = $('form.employeee #joining-date').val();
        var joiningDateCheck = $('form.employeee #joining-date').attr('date-check');
        var sourceInformation = $('form.employeee #source-information').val();
        var degree = $('form.employeee #degree').val();
        var majors = $('form.employeee #majors').val();
        var institute = $('form.employeee #institute').val();
        var year = $('form.employeee #year').val();
        var grade = $('form.employeee #grade').val();
        if (position == '') {
            $('form.employeee #applied_for_position').addClass('invalid');
        }
        if (expectedSalary == '') {
            $('form.employeee #expected-salary').addClass('invalid');
        }
        if (!(new Date(joiningDate) >= new Date(joiningDateCheck))) {
            $('form.employeee #joining-date').addClass('invalid');
        }
        if (sourceInformation == '') {
            $('form.employeee #source-information').addClass('invalid');
        }
        if (degree == '') {
            $('form.employeee #degree').addClass('invalid');
        }
        if (majors == '') {
            $('form.employeee #majors').addClass('invalid');
        }
        if (institute == '') {
            $('form.employeee #institute').addClass('invalid');
        }
        if (year == '') {
            $('form.employeee #year').addClass('invalid');
        }
        if (grade == '') {
            $('form.employeee #grade').addClass('invalid');
        }
        if (position != '' && expectedSalary != '' && new Date(joiningDate) >= new Date(joiningDateCheck) && sourceInformation != '' && degree != '' && majors != '' && institute != '' && year != '' &&
            grade != '') {
            validationChecker = true;
        }
    }
    if (currentStep == 'step3') {
        var checkhistoryCount = 0;
        var entries = $('span#custom-add-more-blocs .getHistoryDetails').length;
        for (var i = 1; i <= entries; i++) {
            var company = $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.company').val();
            var designation = $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.designation').val();
            var duration = $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.duration').val();
            var salary = $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.salary').val();
            var reasons = $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') textarea.reasons_of_leaving').val();
            if (company == '') {
                $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.company').addClass('invalid');
                checkhistoryCount++;
            }
            if (designation == '') {
                $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.designation').addClass('invalid');
                checkhistoryCount++;
            }
            if (duration == '') {
                $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.duration').addClass('invalid');
                checkhistoryCount++;
            }
            if (salary == '') {
                $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') input.salary').addClass('invalid');
                checkhistoryCount++;
            }
            if (reasons == '') {
                $('span#custom-add-more-blocs .getHistoryDetails:nth-child(' + i + ') textarea.reasons_of_leaving').addClass('invalid');
                checkhistoryCount++;
            }
        }
        if (checkhistoryCount == 0) {
            validationChecker = true;
        }
    }
    if (currentStep == 'step4') {
        var first_ref_name = $('form.employeee #first_ref_name').val();
        var second_ref_name = $('form.employeee #second_ref_name').val();
        var third_ref_name = $('form.employeee #third_ref_name').val();
        var first_ref_company = $('form.employeee #first_ref_company').val();
        var second_ref_company = $('form.employeee #second_ref_company').val();
        var third_ref_company = $('form.employeee #third_ref_company').val();
        var first_ref_contact = $('form.employeee #first_ref_contact').val();
        var second_ref_contact = $('form.employeee #second_ref_contact').val();
        var third_ref_contact = $('form.employeee #third_ref_contact').val();
        var hobbies = $('form.employeee #hobbies').val();
        var achievements = $('form.employeee #achievements').val();
        if (first_ref_name == '') {
            $('form.employeee #first_ref_name').addClass('invalid');
        }
        if (second_ref_name == '') {
            $('form.employeee #second_ref_name').addClass('invalid');
        }
        if (third_ref_name == '') {
            $('form.employeee #third_ref_name').addClass('invalid');
        }
        if (first_ref_company == '') {
            $('form.employeee #first_ref_company').addClass('invalid');
        }
        if (second_ref_company == '') {
            $('form.employeee #second_ref_company').addClass('invalid');
        }
        if (third_ref_company == '') {
            $('form.employeee #third_ref_company').addClass('invalid');
        }
        if (first_ref_contact == '') {
            $('form.employeee #first_ref_contact').addClass('invalid');
        }
        if (second_ref_contact == '') {
            $('form.employeee #second_ref_contact').addClass('invalid');
        }
        if (third_ref_contact == '') {
            $('form.employeee #third_ref_contact').addClass('invalid');
        }
        if (hobbies == '') {
            $('form.employeee #hobbies').addClass('invalid');
        }
        if (achievements == '') {
            $('form.employeee #achievements').addClass('invalid');
        }
        if (first_ref_name != '' && second_ref_name != '' && third_ref_name != '' && first_ref_company != '' && second_ref_company != '' && third_ref_company != '' &&
            first_ref_contact != '' && second_ref_contact != '' && third_ref_contact != '' && hobbies != '' && achievements != '') {
            $('form.employeee button[type="submit"]').click();
        }
    }
    if (validationChecker) {
        $('section.steps').hide();
        $('section.' + step).show();
    }
});

// add validtion

$(document).on('keyup change keypress keydown', '.textValidate', function() {
    if ($(this).val() != '') {
        $(this).removeClass('invalid');
    }
});
$(document).on('keyup change keypress keydown', '.emailValidate', function() {
    if ($(this).val() != '' && isEmail($(this).val())) {
        $(this).removeClass('invalid');
    }
});
$(document).on('keyup change keypress keydown', '.maskValidate', function() {
    if ($(this).val() != '' && $(this).val().indexOf('_') == -1) {
        $(this).removeClass('invalid');
    }
});
$(document).on('keyup change keypress keydown', '.selectValidate', function() {
    if ($(this).val() != '') {
        $(this).removeClass('invalid');
    }
});
$(document).on('keyup change keypress keydown', '.dateValidate', function() {
    var getType = $(this).attr('date-type');
    var getCheck = $(this).attr('date-check');
    if (getType == 'max') {
        if (new Date($(this).val()) <= new Date(getCheck)) {
            $(this).removeClass('invalid');
        }
    }
    if (getType == 'min') {
        if (new Date($(this).val()) >= new Date(getCheck)) {
            $(this).removeClass('invalid');
        }
    }
});

// remove validtion

$(document).on('focusout', '.textValidate', function() {
    if ($(this).val() != '') {
        $(this).removeClass('invalid');
    } else {
        $(this).addClass('invalid');
    }
});
$(document).on('focusout', '.emailValidate', function() {
    if ($(this).val() != '' && isEmail($(this).val())) {
        $(this).removeClass('invalid');
    } else {
        $(this).addClass('invalid');
    }
});
$(document).on('focusout', '.maskValidate', function() {
    if ($(this).val() != '' && $(this).val().indexOf('_') == -1) {
        $(this).removeClass('invalid');
    } else {
        $(this).addClass('invalid');
    }
});
$(document).on('focusout', '.selectValidate', function() {
    if ($(this).val() != '') {
        $(this).removeClass('invalid');
    } else {
        $(this).addClass('invalid');
    }
});
$(document).on('focusout', '.dateValidate', function() {
    var getType = $(this).attr('date-type');
    var getCheck = $(this).attr('date-check');
    if (getType == 'max') {
        if (new Date($(this).val()) <= new Date(getCheck)) {
            $(this).removeClass('invalid');
        } else {
            $(this).addClass('invalid');
        }
    }
    if (getType == 'min') {
        if (new Date($(this).val()) >= new Date(getCheck)) {
            $(this).removeClass('invalid');
        } else {
            $(this).addClass('invalid');
        }
    }
});