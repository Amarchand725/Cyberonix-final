<input type="hidden" name="user_id" value="{{ $data['model']->id }}">
<input type="hidden" @if(isset($data['model']->jobHistory) && !empty($data['model']->jobHistory->joining_date)) value="{{ $data['model']->jobHistory->joining_date }}" @endif name="joining_date" required />
<div class="row">
    <div class="mb-3 fv-plugins-icon-container  col-6">
        <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" value="{{ $data['model']->first_name }}" id="first_name" placeholder="Enter first name" name="first_name">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="first_name_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container  col-6">
        <label class="form-label" for="last_name">Last Name</label>
        <input type="text" class="form-control" value="{{ $data['model']->last_name }}" id="last_name" placeholder="Enter last name" name="last_name">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="last_name_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container  col-6">
        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
        <input type="email" id="email" class="form-control" value="{{ $data['model']->email }}" placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="email_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="phone_number">Mobile</label>
        <input type="text" id="phone_number" class="form-control mobileNumber" @if(isset($data['model']->profile) && !empty($data['model']->profile->phone_number)) value="{{ $data['model']->profile->phone_number }}" @endif placeholder="Enter phone number" name="phone_number">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="phone_number_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="d-block form-label">Gender <span class="text-danger">*</span></label>
        <div class="form-check mb-2">
            <input type="radio" id="gender-male" name="gender" class="form-check-input" @if($data['model']->profile->gender == 'male') checked @endif value="male" />
            <label class="form-check-label" for="gender-male">Male</label>
        </div>
        <div class="form-check">
            <input type="radio" id="gender-female" name="gender" class="form-check-input" required value="female" @if($data['model']->profile->gender=='female') checked @endif />
            <label class="form-check-label" for="gender-female">Female</label>
        </div>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="gender_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-6">
        <label class="form-label" for="employment_id">Employee ID</label>
        <input type="text" id="employment_id" value="{{ $data['model']->profile->employment_id }}" name="employment_id" class="form-control phone-mask" placeholder="Enter employment id">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="employment_id_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 col-6">
        <label class="form-label" for="employment_status_id">Employment Status <span class="text-danger">*</span></label>
        <select id="employment_status_id" name="employment_status_id" class="form-select">
            <option value="" selected>Select Status</option>
            @foreach ($data['employment_statues'] as $employment_status)
            <option value="{{ $employment_status->id }}" {{ $data['model']->employeeStatus->employmentStatus->id==$employment_status->id?'selected':'' }}>{{ $employment_status->name }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="employment_status_id_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-6">
        <label class="form-label" for="role_id">Role <span class="text-danger">*</span></label>
        <select id="role_ids" name="role_ids[]" multiple class="form-select select2">
            @foreach ($data['roles'] as $role)
            @php $bool = false; @endphp
            @foreach($data['model']->getRoleNames() as $role_name)
            @if($role->name==$role_name)
            @php $bool = true; @endphp
            @endif
            @endforeach
            @if($bool)
            <option value="{{ $role->id }}" selected>{{ $role->name }}</option>
            @else
            <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endif
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="role_id_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 col-6">
        <label class="form-label" for="designation_id">Designation <span class="text-danger">*</span></label>
        <select id="designation_id" name="designation_id" class="form-select">
            <option value="" selected>Select designation</option>
            @foreach ($data['designations'] as $designation)
            @if(isset($data['model']->jobHistory) && !empty($data['model']->jobHistory->designation_id))
            <option value="{{ $designation->id }}" {{ $data['model']->jobHistory->designation_id==$designation->id?'selected':'' }}>{{ $designation->title }}</option>
            @else
            <option value="{{ $designation->id }}">{{ $designation->title }}</option>
            @endif
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="designation_id_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-6">
        <label class="form-label" for="department_id">Department <span class="text-danger">*</span></label>
        <select id="department_id" name="department_id" class="form-select">
            <option value="" selected>Select department</option>
            @foreach ($data['departments'] as $department)
            <option value="{{ $department->id }}" @if(isset($data['model']->departmentBridge->department_id) && !empty($data['model']->departmentBridge->department_id))
                {{ $data['model']->departmentBridge->department_id==$department->id?'selected':'' }}
                @endif>
                {{ $department->name }}
            </option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="department_id_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 col-6">
        <label class="form-label" for="work_shift_id">Work Shift <span class="text-danger">*</span></label>
        <div class="position-relative">
            <select id="work_shift_id" name="work_shift_id" class="form-select select2">
                <option value="">Select work shift</option>
                @foreach ($data['work_shifts'] as $work_shift)
                <option value="{{ $work_shift->id }}" @if(isset($data['model']->userWorkingShift->workShift) && !empty($data['model']->userWorkingShift->workShift->name))
                    {{ $data['model']->userWorkingShift->workShift->id==$work_shift->id?'selected':'' }}
                    @endif>
                    {{ $work_shift->name }}
                </option>
                @endforeach
            </select>
            <span id="work_shift_id_error" class="text-danger error"></span>
        </div>
    </div>
</div>

<script>
    $('select').each(function () {
    $(this).select2({
            dropdownParent: $(this).parent(),
        });
    });
</script>
