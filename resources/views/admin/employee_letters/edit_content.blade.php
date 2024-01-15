<div class="col-12 col-md-12">
    <label class="form-label" for="employee_id">Employee <span class="text-danger">*</span></label>
    <select class="form-control" id="employee_id" name="employee_id">
        <option value="" selected>Select Employee</option>
        @foreach($employees as $employee)
            <option value="{{ $employee->id }}" {{ $model->employee_id==$employee->id?"selected":"" }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
        @endforeach
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="employee_id_error" class="text-danger error"></span>
</div>
<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
    <select class="form-control" id="title" name="title">
        <option value="" selected>Select Letter</option>
        <option value="joining_letter" {{ $model->title=='joining_letter'?"selected":"" }}>Joining Letter</option>
        <!--<option value="vehical_letter" {{ $model->title=='vehical_letter'?"selected":"" }}>Vehical Letter</option>-->
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="title_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="effective_date">Effective Date <span class="text-danger">*</span></label>
    <input type="date" name="effective_date" id="effective_date" value="{{ $model->effective_date }}" class="form-control" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="effective_date_error" class="text-danger error"></span>
</div>
<div class="col-12 col-md-12 mt-3 validity-date">
    @if($model->title=='joining_letter')
        <label class="form-label" for="validity_date">Validity Date</label>
        <input type="date" name="validity_date" id="validity_date" value="{{ $model->validity_date }}" class="form-control" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="validity_date_error" class="text-danger error"></span>
    @endif
</div>
