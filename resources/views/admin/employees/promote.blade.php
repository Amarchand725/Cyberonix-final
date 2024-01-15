<input type="hidden" name="user_id" id="user_id" value="{{ $data['model']->id }}">
<div class="row">
     <div class="mb-3 col-12">
        <label class="form-label" for="designation_id">Designation <span class="text-danger">*</span></label>
        <select id="designation_id" name="designation_id" class="form-select">
            <option value="" selected>Select designation</option>
            @foreach ($data['designations'] as $designation)
                <option value="{{ $designation->id }}" {{ $data['model']->jobHistory->designation_id==$designation->id?'selected':'' }}>{{ $designation->title }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="designation_id_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12">
        <label class="form-label" for="department_id">Department <span class="text-danger">*</span></label>
        <select id="department_id" name="department_id" class="form-select">
            <option value="" selected>Select department</option>
            @foreach ($data['departments'] as $department)
                <option value="{{ $department->id }}"
                    @if(isset($data['model']->departmentBridge->department_id) && !empty($data['model']->departmentBridge->department_id))
                        {{ $data['model']->departmentBridge->department_id==$department->id?'selected':'' }}
                    @endif>
                        {{ $department->name }}
                </option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="department_id_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12">
        <label class="form-label" for="current_salary">Current Salary <span class="text-danger">*</span></label>
        <input type="text" id="current_salary" readonly @if(!empty($data['model']->salaryHistory->salary)) value="{{ $data['model']->salaryHistory->salary }}" @endif class="form-control" placeholder="Enter salary">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="current_salary_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 col-12">
        <label class="form-label" for="raise_salary">Raise Salary <span class="text-danger">*</span></label>
        <input type="number" id="raise_salary" name="raise_salary" value="" class="form-control" placeholder="Enter raise salary">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="raise_salary_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 col-12">
        <label class="form-label" for="effective_date">Effective Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control flatpickr-validation" value="" id="effective_date" name="effective_date" required />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="effective_date_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 col-12">
        <label class="form-label" for="vehicle_name">Vehicle Name </label>
        <input type="text" class="form-control" name="vehicle_name" id="vehicle_name" placeholder="Enter vehicle name">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="vehicle_name_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 col-12">
        <label class="form-label" for="vehicle_cc">Vehicle Engine Capacity (cc) </label>
        <input type="number" class="form-control" name="vehicle_cc" id="vehicle_cc" placeholder="Enter vehicle Engine Capacity (cc) e.g 1200">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="vehicle_cc_error" class="text-danger error"></span>
    </div>
</div>
