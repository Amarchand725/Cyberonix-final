@extends('user.pre_employees.user-app')
@section('title', $title)

@section('content')
    <form id="steps" action="{{ route('pre_employee.store') }}" method="post" class="show-section employeee h-100" enctype="multipart/form-data">
        @csrf

        <!-- step 1 -->
        <section class="steps step5">
            <div class="step-count">Question 1 / 1</div>
            <h2 class="main-heading text-center">MANAGER</h2>
            <div class="line-break"></div>

            <!-- New Employee Personal Information -->
            <fieldset class="form" id="step5">
                <div class="row justify-content-space-between">
                    <div class="col-md-12 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    MANAGER
                                </label>
                                <small>
                                    <select name="manager_id" class="selectValidate" id="manager_id">
                                        <option value="" selected>Select Manager</option>
                                        @foreach ($data['managers'] as $manager)
                                            <option value="{{ $manager['id'] }}">{{ $manager['first_name'] }} {{ $manager['last_name'] }}</option>
                                        @endforeach
                                    </select>
                                </small>
                                <span class="text-danger">{{ $errors->first('manager_id') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="next-prev-button">
                <a href="javascript:;" type="button" class="next nextQuestionShow" data-current-step="step5" data-step="step1" id="step5btn">Next Question</a>
            </div>
        </section>

        <!-- step 2 -->
        <section class="steps step1" style="display:none;">
            <div class="step-count">Question 1 / 4</div>
            <h2 class="main-heading text-center">PERSONAL INFORMATION</h2>
            <div class="line-break"></div>

            <!-- New Employee Personal Information -->
            <fieldset class="form" id="step1">
                <div class="row justify-content-space-between">
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Your Name (As per CNIC):
                                <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="text" name="name" id="name" class="textValidate" value="{{ old('name') }}" placeholder="Your Name">
                            </small>
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Father Name:
                                <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="text" name="father_name" class="textValidate" value="{{ old('father_name') }}" id="father-name" placeholder="Father's Name">
                            </small>
                            <span class="text-danger">{{ $errors->first('father_name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Email Adress <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="email" name="email" class="emailValidate" value="{{ old('email') }}" id="user-email" placeholder="Email">
                            </small>
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    Date of Birth: <span class="text-danger">*</span>
                                </label>
                                <small>
                                    <input required type="date" name="date_of_birth" max="{{date('Y-m-d')}}" date-type='max' date-check="{{date('Y-m-d')}}" class="dateValidate" value="{{ old('date_of_birth') }}" id="birth-date" placeholder="A brief Description here">
                                </small>
                                <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    CNIC: <span class="text-danger">*</span>
                                </label>
                                <small>
                                    <input required type="text" class="maskValidate cnicValidate" data-inputmask="'mask': '99999-9999999-9'" value="{{ old('cnic') }}" name="cnic" id="CNIC_No" placeholder="">
                                </small>
                                <span class="text-danger">{{ $errors->first('cnic') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    Contact Number: <span class="text-danger">*</span>
                                </label>
                                <small>
                                    <input required type="text" name="contact_no" class="maskValidate contactValidate" value="{{ old('contact_no') }}" data-inputmask="'mask': '0399-9999999'" ="" id="contact-no" type="number" maxlength="12">
                                </small>
                                <span class="text-danger">{{ $errors->first('contact_no') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    Emergency Contact Number: <span class="text-danger">*</span>
                                </label>
                                <small>
                                    <input required type="text" name="emergency_number" class="maskValidate emergencyValidate" value="{{ old('emergency_number') }}" data-inputmask="'mask': '0399-9999999'" ="" id="emergency-no" type="number" maxlength="12">
                                </small>
                                <span class="text-danger">{{ $errors->first('emergency_number') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    Address:
                                    <span class="text-danger">*</span>
                                </label>
                                <small>
                                    <input required type="text" value="{{ old('address') }}" class="textValidate" name="address" id="address" placeholder="Enter your address.">
                                </small>
                                <span class="text-danger">{{ $errors->first('address') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    Apartment, Suite, etc: <span class="text-danger">*</span>
                                </label>
                                <small>
                                    <input required type="text" name="apartment" class="textValidate"  value="{{ old('apartment') }}" id="apartment" placeholder=" Enter apartment">
                                </small>
                                <span class="text-danger">{{ $errors->first('apartment') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="text-field-input">
                            <div class="input-field">
                                <label>
                                    Marital Status
                                </label>
                                <small>
                                    <select name="marital_status" class="selectValidate" id="status">
                                        <option value="single" {{ old('marital_status')=='single'?'selected':'' }}>Single</option>
                                        <option value="married" {{ old('marital_status')=='married'?'selected':'' }}>Married</option>
                                    </select>
                                </small>
                                <span class="text-danger">{{ $errors->first('marital_status') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="next-prev-button">
                <a href="javascript:;" type="button" class="prev prevQuestionShow" data-step="step5">Previous Question</a>
                <a href="javascript:;" type="button" class="next nextQuestionShow" data-current-step="step1" data-step="step2" id="step1btn">Next Question</a>
            </div>
        </section>

        <!-- Applied Position & Academic -->
        <section class="steps step2" style="display:none;">
            <div class="step-count">Question 2 / 4</div>
            <h2 class="main-heading text-center">Positions</h2>
            <div class="line-break"></div>

            <!-- form -->
            <fieldset class="form" id="step2">
                <div class="row justify-content-space-between">
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Applied Position <span class="text-danger">*</span>
                            </label>
                            <small>
                                <select required name="applied_for_position" id="applied_for_position" class="control-form textValidate">
                                    <option value="" selected>Select position applied for</option>
                                    @foreach ($data['positions'] as $position)
                                        <option value="{{ $position->id }}" {{ old('applied_for_position')==$position->id?'selected':'' }}>{{ $position->title }}</option>
                                    @endforeach
                                </select>
                            </small>
                            <span class="text-danger">{{ $errors->first('applied_for_position') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Expected Salary:<span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="number" id="expected-salary" value="{{ old('expected_salary') }}" name="expected_salary" placeholder="Enter your expected salary." class="textValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('expected_salary') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Expected Date of Joining: <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="date" name="expected_joining_date" min="{{date('Y-m-d')}}" date-type='min' date-check="{{date('Y-m-d')}}" value="{{ old('expected_joining_date') }}" id="joining-date" placeholder="A brief Description here" class="dateValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('expected_joining_date') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Source of Information for this post: <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="text" name="source_of_this_post" value="{{ old('source_of_this_post') }}" id="source-information" placeholder="Enter url of source of this post." class="textValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('source_of_this_post') }}</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <h2 class="main-heading text-center">ACADEMIC</h2>
                        <hr>
                    </div>

                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Degree: <span class="text-danger">*</span>
                            </label>
                            <small>
                                <select required name="degree" id="degree" class="control-form textValidate">
                                    <option value="" selected>Select your last degree</option>
                                    <option value="phd" {{ old('degree')=='phd'?'selected':'' }}>PHD Degree</option>
                                    <option value="mphil" {{ old('degree')=='mphil'?'selected':'' }}>Master of Philosophy</option>
                                    <option value="master" {{ old('degree')=='master'?'selected':'' }}>Master's</option>
                                    <option value="bachelor" {{ old('degree')=='bachelor'?'selected':'' }}>Bachelor's</option>
                                    <option value="intermediate" {{ old('degree')=='intermediate'?'selected':'' }}>Intermediate</option>
                                    <option value="matriculation" {{ old('degree')=='matriculation'?'selected':'' }}>Matriculation</option>
                                </select>
                            </small>
                            </small>
                            <span class="text-danger">{{ $errors->first('degree') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 tab-100">
                        <div class="input-field">
                            <label>
                                Majors:<span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="text" name="major_subject" value="{{ old('major_subject') }}" id="majors" placeholder="Enter major subject" class="textValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('major_subject') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-field">
                            <label>
                                Institute: <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="text" name="institute" value="{{ old('institute') }}" id="institute" placeholder="Enter name of the insitute." class="textValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('institute') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-field">
                            <label>
                                Year: <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="number" name="passing_year" value="{{ old('passing_year') }}" id="year" placeholder="Enter passing year e.g 2023" class="textValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('passing_year') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-field">
                            <label>
                                Grade/GPA: <span class="text-danger">*</span>
                            </label>
                            <small>
                                <input required type="text" name="grade_or_gpa" value="{{ old('grade_or_gpa') }}" id="grade" placeholder="Enter grade or gpa" class="textValidate">
                            </small>
                            <span class="text-danger">{{ $errors->first('grade_or_gpa') }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="next-prev-button">
                <a href="javascript:;" type="button" class="prev prevQuestionShow" data-step="step1">Previous Question</a>
                <a href="javascript:;" type="button" class="next nextQuestionShow" data-current-step="step2" data-step="step3" id="step2btn">Next Question</a>
            </div>
        </section>

        <!-- Employment History -->
        <section class="steps step3" style="display:none;">
            <div class="step-count">Question 3 / 4</div>
            <h3 class="text-dark text-center">EMPLOYMENT HISTORY</h3>
            <div class="line-break"></div>

            <!-- form -->
            <fieldset class="form" id="step3">
                <button class="btn add-btn" type="button" id="add-more-history-btn" title="Add more history"><i class="fa fa-plus"></i></button>

                <span id="custom-add-more-blocs">
                        <div class="row mt-3 getHistoryDetails" id="custom-block">
                            <div class="col-md-6">
                                <div class="input-field">
                                    <label for="company">
                                        Company: <span class="text-danger">*</span>
                                    </label>
                                    <input required type="text" name="companies[]" id="company" class="textValidate company" placeholder="Company">
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-field">
                                    <label for="phone">
                                        Designation: <span class="text-danger">*</span>
                                    </label>
                                    <input required type="text" name="designations[]" id="designation" class="textValidate designation" placeholder="Designation">
                                    <span class="text-danger">{{ $errors->first('designation') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-field">
                                    <label>
                                        Duration: <span class="text-danger">*</span>
                                    </label>
                                    <input required type="text" name="durations[]" id="duration" name="mail" class="textValidate duration" placeholder="Duration">
                                    <span class="text-danger">{{ $errors->first('duration') }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-field">
                                    <label for="message" class="">
                                        Salary: <span class="text-danger">*</span>
                                    </label>
                                    <input required type="number" id="salary" value="{{ old('salary') }}" name="salaries[]" id="message" placeholder="Enter your salary" class="textValidate salary">
                                    <span class="text-danger">{{ $errors->first('salary') }}</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="input-field">
                                    <label for="">
                                        Reason of Leaving: <span class="text-danger">*</span>
                                    </label>
                                    <textarea required  name="reasons_of_leaving[]" id="reason-leaving" class="textValidate reasons_of_leaving" placeholder="A brief Description here"></textarea>
                                    <span class="text-danger">{{ $errors->first('reason_of_leaving') }}</span>
                                </div>
                            </div>
                        </div>
                </span>
            </fieldset>
            <div class="next-prev-button">
                <a href="javascript:;" type="button" class="prev prevQuestionShow" data-step="step2">Previous Question</a>
                <a href="javascript:;" type="button" class="next nextQuestionShow" data-current-step="step3" data-step="step4" id="step2btn">Next Question</a>
            </div>
        </section>

        <!-- References & Resume -->
        <section class="steps step4" style="display:none;">
            <div class="step-count">Question 4 / 4</div>
            <h3 class="text-dark text-center">REFERENCES</h3>
            <div class="line-break"></div>

            <!-- form -->
            <fieldset class="form" id="step4">
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-field">
                            <label for="company">
                                Name: <span class="text-danger">*</span>
                            </label>
                            <input required  type="text" name="first_ref_name" value="{{ old('first_ref_name') }}" id="first_ref_name" class="inp textValidate" placeholder="Enter your first reference name">
                            <span class="text-danger">{{ $errors->first('first_ref_name') }}</span>
                            <input type="text" name="second_ref_name" value="{{ old('second_ref_name') }}" id="" class="inp textValidate" placeholder="Enter your second reference name">
                            <span class="text-danger">{{ $errors->first('second_ref_name') }}</span>
                            <input type="text" name="third_ref_name" value="{{ old('third_ref_name') }}" id="" class="inp textValidate" placeholder="Enter your third reference name">
                            <span class="text-danger">{{ $errors->first('third_ref_name') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-field">
                            <label for="phone">
                                Company: <span class="text-danger">*</span>
                            </label>
                            <input required  type="text" name="first_ref_company" value="{{ old('first_ref_company') }}" id="first_ref_company" class="inp textValidate" placeholder="Enter your first reference company name">
                            <span class="text-danger">{{ $errors->first('first_ref_company') }}</span>
                            <input type="text" name="second_ref_company" value="{{ old('second_ref_company') }}" id="" class="inp textValidate" placeholder="Enter your second reference company name">
                            <span class="text-danger">{{ $errors->first('second_ref_company') }}</span>
                            <input type="text" name="third_ref_company" value="{{ old('third_ref_company') }}" id="" class="inp textValidate" placeholder="Enter your third reference reference name">
                            <span class="text-danger">{{ $errors->first('third_ref_company') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-field">
                            <label>
                                Contact No: <span class="text-danger">*</span>
                            </label>
                            <input required  type="text" name="first_ref_contact" data-inputmask="'mask': '0399-9999999'" value="{{ old('first_ref_contact') }}" id="first_ref_contact" class="inp maskValidate" placeholder="Enter your first reference contact">
                            <span class="text-danger">{{ $errors->first('first_ref_contact') }}</span>
                            <input type="text" name="second_ref_contact" data-inputmask="'mask': '0399-9999999'" value="{{ old('second_ref_contact') }}" id="" class="inp maskValidate" placeholder="Enter your second reference contact">
                            <span class="text-danger">{{ $errors->first('second_ref_contact') }}</span>
                            <input type="text" name="third_ref_contact" data-inputmask="'mask': '0399-9999999'" value="{{ old('third_ref_contact') }}" id="" class="inp maskValidate" placeholder="Enter your third reference contact">
                            <span class="text-danger">{{ $errors->first('third_ref_contact') }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-field">
                            <label for="message" class="">
                                Hobbies and Interests <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="hobbies_and_interests" id="hobbies" class="textValidate" placeholder="Enter hobbies & intrestes">
                            <span class="text-danger">{{ $errors->first('hobbies_and_interests') }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-field">
                            <label for="">
                                Achievements: <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="achievements" value="{{ old('achievements') }}" class="textValidate" id="achievements" placeholder="Enter achievements">
                            <span class="text-danger">{{ $errors->first('achievements') }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-field">
                            <label for="">
                                Portfolio link:
                            </label>
                            <input type="text" name="portfolio_link" value="{{ old('portfolio_link') }}" id="portfolio" placeholder="Enter portfolio link">
                            <span class="text-danger">{{ $errors->first('portfolio_link') }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="input-field">
                            <label for="">
                                Upload Resume: <small>Accept only PDF File.</small>
                            </label>
                            <input type="file" accept="application/pdf" name="resume" id="resume">
                            <span class="text-danger">{{ $errors->first('resume') }}</span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="next-prev-button">
                <a href="javascript:;" type="button" class="prev prevQuestionShow" data-step="step3">Previous Question</a>
                <a href="javascript:;" type="button" class="next nextQuestionShow" data-current-step="step4" data-step="step4" id="step4btn">Save</a>
                <button id="step4btn" type="submit" class="apply sub-btn" style="display:none;">Save</button>
            </div>
        </section>
    </form>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#add-more-history-btn', function(){
            var html = '<div id="custom-block" class="more_experiences getHistoryDetails row mt-3">'+
                        '<div class="two-btn">'+
                            '<div class="btn-width">'+
                                '<button class="btn add-btn" type="button" id="add-more-history-btn" title="Add more history"><i class="fa fa-plus revealfield"></i></button>'+
                            '</div>'+
                            '<div class="btn-width">'+
                                '<button class="btn btn-remove" type="button" id="remove-block-btn" title="Remove Block"><i class="fa-solid fa-trash"></i></button>' +
                            '</div>'+
                        '</div>'+
                        '<div class="row mt-3">'+
                            '<div class="col-md-6">'+
                                '<div class="input-field">'+
                                    '<label for="company">'+
                                        'Company: <span class="text-danger">*</span>'+
                                    '</label>'+
                                    '<input required type="text" name="companies[]" id="company" class="textValidate company" placeholder="Company">'+
                                    '<span class="text-danger">{{ $errors->first("company") }}</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<div class="input-field">'+
                                    '<label for="phone">'+
                                        'Designation: <span class="text-danger">*</span>'+
                                    '</label>'+
                                    '<input required type="text" name="designations[]" id="designation" class="textValidate designation" placeholder="Designation">'+
                                    '<span class="text-danger">{{ $errors->first("designation") }}</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<div class="input-field">'+
                                    '<label>'+
                                        'Duration: <span class="text-danger">*</span>'+
                                    '</label>'+
                                    '<input required type="text" name="durations[]" id="duration" name="mail" class="textValidate duration" placeholder="Duration">'+
                                    '<span class="text-danger">{{ $errors->first("duration") }}</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-6">'+
                                '<div class="input-field">'+
                                    '<label for="message" class="">'+
                                        'Salary: <span class="text-danger">*</span>'+
                                    '</label>'+
                                    '<input required type="number" id="salary" name="salaries[]" class="textValidate salary" placeholder="Enter your salary">'+
                                    '<span class="text-danger">{{ $errors->first("salary") }}</span>'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-12">'+
                                '<div class="input-field">'+
                                    '<label for="">'+
                                        'Reason of Leaving: <span class="text-danger">*</span>'+
                                    '</label>'+
                                    '<textarea required  name="reasons_of_leaving[]" id="reason-leaving" class="textValidate reasons_of_leaving" placeholder="A brief Description here"></textarea>'+
                                    '<span class="text-danger">{{ $errors->first("reason_of_leaving") }}</span>'+
                                '</div>'+
                            '</div>'+

                        '</div>';
            $('#custom-add-more-blocs').append(html);
        });

        $(document).on('click','#remove-block-btn', function(){
            $(this).parents('#custom-block').remove();
        });


        $(":input").inputmask();
		$('#CNIC_No').mask("99999-9999999-9");

    </script>
@endpush
