<div class="row">
    <div class="col-md-6">
        <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
        <input type="text" id="email" name="email" value="{{ $data['user_email'] }}" class="form-control" placeholder="Enter email" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="email_error" class="text-danger error"></span>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="title">Manager <span class="text-danger">*</span></label>
        <select name="manager_id" id="manager_id" class="form-control select2">
            @if(isset($managers) && count($managers) > 0)
            @foreach ($managers as $manager)
            <option value="{{ $manager->id }}" @if(isset($data['model']->manager_id) && !empty($data['model']->manager_id) && $data['model']->manager_id == $manager->id) selected @endif>{{ getUserName($manager) }}</option>
            @endforeach
            @endif
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="manager_id_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <label class="form-label" for="joining_date">Joining Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="joining_date" name="joining_date">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="joining_date_error" class="text-danger error"></span>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="salary">Salary <span class="text-danger">*</span></label>
        <input type="number" id="salary" name="salary" value="{{ $data['expected_salary'] }}" class="form-control" placeholder="Enter salary" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="salary_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <label class="form-label" for="designation_id">Designation</label>
        <select class="form-select select2" id="designation_id" name="designation_id">
            <option value="" selected>Select designation</option>
            @foreach ($data['designations'] as $designation)
                <option value="{{ $designation->id }}">{{ $designation->title }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="designation_id_error" class="text-danger error"></span>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="custom_designation">Custom Designation</label>
        <input type="text" id="custom_designation" value="" name="custom_designation" class="form-control" placeholder="Enter custom designation" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="custom_designation_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <label class="form-label" for="department_id">Department</label>
        <select class="form-select select2" id="department_id" name="department_id">
            <option value="" selected>Select department</option>
            @foreach ($data['departments'] as $department_id)
                <option value="{{ $department_id->id }}" {{ $data['model']->manager_id==$department_id->manager_id?'selected':'' }}>{{ $department_id->name }} - {{ $department_id->manager->first_name??'' }} {{ $department_id->manager->last_name??'' }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="department_id_error" class="text-danger error"></span>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="working_shift_id">Shift <span class="text-danger">*</span></label>
        <select class="form-select select2" id="working_shift_id" name="working_shift_id">
            <option value="" selected>Select shift</option>
            @foreach ($data['shifts'] as $shift)
                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="working_shift_id_error" class="text-danger error"></span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="is_vehicle">Vehicle</label>
        <select class="form-select select2 is_vehicle" id="is_vehicle" name="is_vehicle">
            <option value="1">Yes</option>
            <option value="0" selected>No</option>
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="is_vehicle_error" class="text-danger error"></span>
    </div>
    <span class="vehicle-content"></span>
</div>

<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="note">Note ( <small>Optional</small> )</label>
    <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note"></textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="note_error" class="text-danger error"></span>
</div>

@if(isset($profile_img) || isset($cnic_front) || isset($cnic_back))
<div class="row mt-3">
    <div class="col-md-4">
        <label class="form-label" for="working_shift_id">Profile Image</label>
        <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ isset($profile_img) && !empty($profile_img) && file_exists(public_path('admin/assets/img/avatars').'/'.$profile_img) ? $profile_img : 'default.png' }}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="max-width: 130px !important; width: 130px; height: 100px !important;">
    </div>

    <div class="col-md-4">
        <label class="form-label" for="working_shift_id">CNIC Front Image</label>
        <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ isset($cnic_front) && !empty($cnic_front) && file_exists(public_path('admin/assets/img/avatars').'/'.$cnic_front) ? $cnic_front : 'default.png' }}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="max-width: 150px !important; width: 150px; height: 100px !important;">
    </div>

    <div class="col-md-4">
        <label class="form-label" for="working_shift_id">CNIC Back Image</label>
        <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ isset($cnic_back) && !empty($cnic_back) && file_exists(public_path('admin/assets/img/avatars').'/'.$cnic_back) ? $cnic_back : 'default.png' }}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" style="max-width: 150px !important; width: 150px; height: 100px !important;">
    </div>
</div>
@endif
