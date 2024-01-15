<div class="row">
    <div class="col-12">
        <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="subject" name="subject" value="{{ $model->subject }}" placeholder="subject" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="subject_error" class="text-danger error"></span>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="employment_status_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12">
        <label class="form-label" for="resignation_date">Resignation Date <span class="text-danger">*</span></label>
        <input type="date" class="form-control" value="{{ $model->resignation_date }}" id="resignation_date" name="resignation_date">
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="resignation_date_error" class="text-danger error"></span>
    </div>
</div>

<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="reason_for_resignation">Reason for resignation </label>
    <textarea class="form-control" rows="5" name="reason_for_resignation" id="reason_for_resignation" placeholder="Enter reason for resignation here">{{ $model->reason_for_resignation }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="reason_for_resignation_error" class="text-danger error"></span>
</div>