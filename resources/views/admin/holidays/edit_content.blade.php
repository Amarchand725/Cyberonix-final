<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
        <input type="text" id="name" name="name" value="{{ $model->name }}" class="form-control" placeholder="Enter name here"/>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="name_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <label class="d-block form-label">Holiday Type </label>
        <select name="type" id="type" class="form-control check-type">
            <option value="universal" {{ $model->type=="universal"?'selected':'' }}>Universal</option>
            <option value="customizable" {{ $model->type=="customizable"?'selected':'' }}>Customizable</option>
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="type_error" class="text-danger error"></span>
    </div>
</div>
<div @if($model->type=="customizable") class="row mt-2" @else class="row mt-2 d-none" @endif  id="customizeEmployeesRow">
    <div class="col-md-12">
        <label class="form-label" for="customize_employees">Customize Employees</label>
        <select id="employees" name="employees[]" multiple class="form-select select2">
            @foreach ($employees as $employee)
                @php $bool = false; @endphp
                @foreach($model->hasCustomizedEmployees as $customizeEmployee)
                    @if($employee->id==$customizeEmployee->employee_id)
                        @php $bool = true; @endphp
                    @endif
                @endforeach
                @if($bool)
                    <option value="{{ $employee->id }}" selected>{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})</option>
                @else
                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})</option>
                @endif
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="customize_employees_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="start_at">Start Date <span class="text-danger">*</span></label>
        <input type="date" id="start_at" name="start_at" value="{{ $model->start_at }}" class="form-control" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="start_at_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="end_at">End Date <span class="text-danger">*</span></label>
        <input type="date" id="end_at" name="end_at" value="{{ $model->end_at }}" class="form-control" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="end_at_error" class="text-danger error"></span>
    </div>
</div>

<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="description">description <span class="text-danger">*</span></label>
    <textarea class="form-control" rows="5" name="description" id="description" placeholder="Enter description here">{{ $model->description }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="description_error" class="text-danger error"></span>
</div>

<script>
    CKEDITOR.replace('description');
    $('.form-select').each(function() {
        $(this).select2({
            dropdownParent: $(this).parent(),
        });
    });
</script>
