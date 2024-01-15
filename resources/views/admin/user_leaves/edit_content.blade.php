<div class="row">
    <div class="col-12">
        <label class="form-label" for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
        <select name="leave_type_id" id="leave_type_id" class="form-control">
            <option value="" selected>Select leave type</option>
            @if(isset($leave_types))
                @foreach ($leave_types as $leave_type)
                    <option value="{{ $leave_type->id }}" {{ $model->leave_type_id==$leave_type->id?'selected':'' }}>{{ $leave_type->name }}</option>
                @endforeach
            @endif
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="leave_type_id_error" class="text-danger error"></span>
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
    <label class="form-label" for="reason">Reason <span class="text-danger">*</span></label>
    <textarea class="form-control" rows="5" name="reason" id="reason" placeholder="Enter reason here">{{ $model->reason }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="reason_error" class="text-danger error"></span>
</div>