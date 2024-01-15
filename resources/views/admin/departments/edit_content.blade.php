<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="name" value="{{ $data['model']->name??'' }}" placeholder="Enter department name" name="name">
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_error" class="text-danger error"></span>
</div>
<div class="mb-3">
    <label class="form-label" for="parent_department_id">Parent Department <span class="text-danger">*</span></label>
    <div class="position-relative">
        <select id="parent_department_id" name="parent_department_id" class="form-control">
            <option value="">Select parent department</option>
            @foreach ($data['departments'] as $department)
                <option value="{{ $department->id }}" {{ $data['model']->parent_department_id==$department->id?'selected':'' }}>{{ $department->name }}</option>
            @endforeach
        </select>
        <span id="parent_department_id_error" class="text-danger error"></span>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="manager_id">Manager</label>
    <div class="position-relative">
        <select id="manager_id" name="manager_id" class="form-control">
            <option value="">Select manager</option>
            @if(isset($data['model']->manager) && !empty($data['model']->manager->id))
                @foreach ($data['department_managers'] as $user)
                    <option value="{{ $user['id'] }}" {{ $data['model']->manager->id==$user['id']?'selected':'' }}>{{ $user['first_name'] }} {{ $user['last_name'] }}</option>
                @endforeach
            @else
                @foreach ($data['department_managers'] as $user)
                    <option value="{{ $user['id'] }}">{{ $user['first_name'] }} {{ $user['last_name'] }}</option>
                @endforeach
            @endif
        </select>
        <span id="manager_id_error" class="text-danger error"></span>
    </div>
</div>
<div class="mb-3">
    <label class="form-label" for="description">Description ( <small>Optional</small> )</label>
    <textarea name="description" id="description" class="form-control" placeholder="Enter description">{{ $data['model']->description }}</textarea>
</div>

<script>
    CKEDITOR.replace('description');
    $('.form-select').select2();
</script>
