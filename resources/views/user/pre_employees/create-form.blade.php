@extends('user.pre_employees.user-app')
@section('title', $title)
@section('content')
<form id="preEployeeForm" action="{{ route('office_boys.storePreEmployees') }}" method="post" class="h-100" enctype="multipart/form-data">
    @csrf

    <h2 class="main-heading text-center">PERSONAL INFORMATION</h2>
    <div class="line-break"></div><br>
    @if(Session::has("error"))
    <div class="alert alert-danger" style="font-weight: bold;text-align: center;">
        {{Session::get("error")}}
    </div>
    @endif
    <!-- New Employee Personal Information -->
    <div class="row justify-content-space-between">
        <div class="col-md-12 tab-100">
            <div class="text-field-input">
                <div class="input-field">
                    <label>
                        MANAGER <span class="text-danger">*</span>
                    </label>
                    <small>
                        <select required readonly name="manager_id" class="selectValidate" id="manager_id">
                            @if(isset($managers) && count($managers) > 0)
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" selected>{{ getUserName($manager) ?? null }}</option>
                                @endforeach
                            @endif
                        </select>
                    </small>
                    @error('manager_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="input-field">
                <label>
                    Your Name (As per CNIC):
                    <span class="text-danger">*</span>
                </label>
                <small>
                    <input required type="text" name="name" id="name" class="textValidate" value="{{ old('name') }}" placeholder="Your Name">
                </small>
                @error('name')
                <span class="text-danger">{{ $message }}</span>
                @enderror

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
                @error('father_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
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
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="text-field-input">
                <div class="input-field">
                    <label>
                        Date of Birth: <span class="text-danger">*</span>
                    </label>
                    <small>
                        <input required type="date" name="date_of_birth" max="{{ date('Y-m-d') }}" date-type='max' date-check="{{ date('Y-m-d') }}" class="dateValidate" value="{{ old('date_of_birth') }}" id="birth-date" placeholder="A brief Description here">
                    </small>
                    @error('date_of_birth')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
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

                    @error('cnic')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
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
                        <input required type="text" name="contact_no" class="maskValidate contactValidate" value="{{ old('contact_no') }}" data-inputmask="'mask': '0399-9999999'"="" id="contact-no" type="number" maxlength="12">
                    </small>
                    @error('contact_no')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

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
                        <input required type="text" name="emergency_number" class="maskValidate emergencyValidate" value="{{ old('emergency_number') }}" data-inputmask="'mask': '0399-9999999'"="" id="emergency-no" type="number" maxlength="12">
                    </small>
                    @error('emergency_number')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="input-field">
                <label>
                    Applied Position <span class="text-danger">*</span>
                </label>
                <small>
                    <select required name="applied_for_position" id="applied_for_position" class="control-form textValidate">
                        <option value="" selected>Select position applied for</option>
                        @if(isset($positions) && count($positions) > 0)
                        @foreach ($positions as $position)
                        <option value="{{ $position->id }}" {{ old('applied_for_position')==$position->id?'selected':'' }}>{{ $position->title }}</option>
                        @endforeach
                        @endif
                    </select>
                </small>

                @error('applied_for_position')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="input-field">
                <label>
                    Salary:<span class="text-danger">*</span>
                </label>
                <small>
                    <input required type="number" id="expected-salary" value="{{ old('expected_salary') }}" name="expected_salary" placeholder="Enter your expected salary." class="textValidate">
                </small>

                @error('expected_salary')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="input-field">
                <label>
                    Joining Date: <span class="text-danger">*</span>
                </label>
                <small>
                    <input required type="date" name="expected_joining_date" value="{{ old('expected_joining_date') }}" id="joining-date" placeholder="A brief Description here">
                </small>

                @error('expected_joining_date')
                <span class="text-danger">{{ $message }}</span>
                @enderror
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

                @error('source_of_this_post')
                <span class="text-danger">{{ $message }}</span>
                @enderror
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
                    @error('address')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror


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
                        <input required type="text" name="apartment" class="textValidate" value="{{ old('apartment') }}" id="apartment" placeholder=" Enter apartment">
                    </small>
                    @error('apartment')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

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
                            <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Single
                            </option>
                            <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Married
                            </option>
                        </select>
                    </small>
                    @error('marital_status')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="text-field-input">
                <div class="input-field">
                    <label>
                        CNIC Image Front:
                        <span class="text-danger">*</span>
                    </label>
                    <small>
                        <input type="file" value="{{ old('nic_front_image') }}" class="textValidate" name="nic_front_image" id="nic_front_image" accept="image/*" required>
                    </small>
                    @error('nic_front_image')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
        </div>
        <div class="col-md-6 tab-100">
            <div class="text-field-input">
                <div class="input-field">
                    <label>
                        CNIC Image Back:
                        <span class="text-danger">*</span>
                    </label>
                    <small>
                        <input type="file" value="{{ old('nic_back_image') }}" class="textValidate" name="nic_back_image" id="nic_back_image" accept="image/*" required>
                    </small>
                    @error('nic_back_image')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
        </div>
        <div class="col-md-12 tab-100">
            <div class="text-field-input">
                <div class="input-field">
                    <label>
                        Profile Imagae:
                        <span class="text-danger">*</span>
                    </label>
                    <small>
                        <input type="file" value="{{ old('profile_image') }}" class="textValidate" name="profile_image" id="profile_image" accept="image/*" required>
                    </small>
                    @error('profile_image')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror

                </div>
            </div>
        </div>
    </div>
    <div class="next-prev-button">
        <button class="next submitBtnSingleFrom" data-current-step="step1">Submit</button>
    </div>
</form>
@endsection
@push('scripts')
<script>
    $(":input").inputmask();

    $(document).on('click', '.submitBtnSingleFrom', function() {
        var validationChecker = false;
        var manager_id = $('#manager_id').val();
        var name = $('#name').val();
        var fathername = $('#father-name').val();
        var useremail = $('#user-email').val();
        var birthdate = $('#birth-date').val();
        var birthdateCheck = $('#birth-date').attr('date-check');
        var cnic_no = $('#CNIC_No').val();
        var contactno = $('#contact-no').val();
        var emergencyno = $('#emergency-no').val();
        var address = $('#address').val();
        var apartment = $('#apartment').val();
        var status = $('#status').val();
        var position = $('#applied_for_position').val();
        var expected_salary = $('#expected-salary').val();
        var joining_date = $('#joining-date').val();
        var source_information = $('#source-information').val();

        if (manager_id == '') {
            $('#manager_id').addClass('invalid');
        }

        if (name == '') {
            $('#name').addClass('invalid');
        }
        if (fathername == '') {
            $('#father-name').addClass('invalid');
        }
        if (address == '') {
            $('#address').addClass('invalid');
        }
        if (apartment == '') {
            $('#apartment').addClass('invalid');
        }
        if (status == '') {
            $('#status').addClass('invalid');
        }
        if (!(new Date(birthdate) <= new Date(birthdateCheck))) {
            $('#birth-date').addClass('invalid');
        }
        if (useremail == '' || !isEmail(useremail)) {
            $('#user-email').addClass('invalid');
        }
        if (cnic_no == '' || cnic_no.indexOf('_') !== -1) {
            $('#CNIC_No').addClass('invalid');
        }
        if (contactno == '' || contactno.indexOf('_') !== -1) {
            $('#contact-no').addClass('invalid');
        }
        if (emergencyno == '' || emergencyno.indexOf('_') !== -1) {
            $('#emergency-no').addClass('invalid');
        }
        if (position == '') {
            $('#applied_for_position').addClass('invalid');
        }
        if (expected_salary == '') {
            $('#expected-salary').addClass('invalid');
        }
        if (joining_date == '') {
            $('#joining-date').addClass('invalid');
        }
        if (source_information == '') {
            $('#source-information').addClass('invalid');
        }
        if (name != '' && fathername != '' && address != '' && apartment != '' && status != '' && manager_id != '' && new Date(birthdate) <= new Date(birthdateCheck) && useremail != '' && isEmail(useremail) &&
            cnic_no != '' && cnic_no.indexOf('_') == -1 && contactno != '' && contactno.indexOf('_') == -1 && emergencyno != '' && emergencyno.indexOf('_') == -1 && position != '' && expected_salary != '' && new joining_date != '' && source_information != '') {
            validationChecker = true;
        }

        if (validationChecker) {
            $("#preEployeeForm").submit();
        } else {
            return false;
        }
    });
</script>
@endpush
