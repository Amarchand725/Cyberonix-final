<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="name">Name</label>
    <input type="text" class="form-control" id="name" value="{{ $data['model']->name??'' }}" placeholder="Enter department name" name="name">
    <div class="fv-plugins-message-container invalid-feedback"></div>
</div>
<div class="mb-3">
    <label class="form-label" for="parent_department_id">Parent Department</label>
    <div class="position-relative">
        <select id="parent_department_id" name="parent_department_id" class="select2 form-select">
            <option value="">Select parent department</option>
            @foreach ($data['departments'] as $department)
                <option value="{{ $department->id }}" {{ $data['model']->parent_department_id==$department->id?'selected':'' }}>{{ $department->name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="manager_id">Manager</label>
    <div class="position-relative">
        <select id="manager_id" name="manager_id" class="select2 form-select">
            <option value="">Select manager</option>
            @foreach ($data['users'] as $user)
                <option value="{{ $user->id }}" {{ $data['model']->manager->id==$user->id?'selected':'' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="work_shift_id">Work Shift</label>
    <div class="position-relative">
        <select id="work_shift_id" name="work_shift_id" class="select2 form-select">
            <option value="">Select work shift</option>
            @foreach ($data['work_shifts'] as $work_shift)
                <option value="{{ $work_shift->id }}" @if(!empty($data['model']->departmentWorkShift->work_shift_id)) {{ $data['model']->departmentWorkShift->work_shift_id==$work_shift->id?'selected':'' }} @endif>{{ $work_shift->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label" for="location">Location ( <small>Optional</small> )</label>
    <input type="text" id="location" class="form-control phone-mask" value="{{ $data['model']->location }}" placeholder="Enter location" name="location">
</div>
<div class="mb-3">
    <label class="form-label" for="description">Description ( <small>Optional</small> )</label>
    <textarea name="description" id="description" class="form-control" placeholder="Enter description">{{ $data['model']->description }}</textarea>
</div>
