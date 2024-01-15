<div class="row">
    <div class="col-12 col-md-12">
        <label class="form-label" for="user_id">Employees <span class="text-danger">*</span></label>
        <select id="user_id" name="user_id" class="form-select select2">
            @if(isset($employees))
                @foreach ($employees as $employee)
                    @php $department_name = ''; @endphp
                    @if(isset($employee->departmentBridge->department) && !empty($employee->departmentBridge->department->name))
                        @php $department_name = '( '. $employee->departmentBridge->department->name.' )'; @endphp
                    @endif
                    <option value="{{ $employee->id }}" {{ $model->user_id==$employee->id?'selected':'' }}>{{ $employee->first_name }} {{ $employee->last_name }} {{ $department_name }}</option>
                @endforeach
            @endif
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="user_id_error" class="text-danger error"></span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 col-md-12">
        <label class="form-label" for="status">Status </label>
        <select id="status" name="status" class="form-control">
            <option value="1" {{ $model->status==1?'selected':'' }}>Active</option>
            <option value="0" {{ $model->status==0?'selected':'' }}>In-Active</option>
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="status_error" class="text-danger error"></span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 col-md-12">
        <label class="form-label" for="note">Note </label>
        <textarea name="note" id="note" class="form-control" rows="5" placeholder="Enter important note.">{{ $model->note }}</textarea>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="note_error" class="text-danger error"></span>
    </div>
</div>
