<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="name" value="{{ $model->name }}" placeholder="Enter Working Shift Name e.g Night" name="name">
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_error" class="text-danger error"></span>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="start_date" value="{{ $model->start_date }}" id="start_date">
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="start_date_error" class="text-danger error"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="end_date">End Date</label>
            <input type="date" class="form-control" name="end_date" value="{{ $model->end_date }}" id="end_date">
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="end_date_error" class="text-danger error"></span>
        </div>
    </div>
</div>
<div class="mb-3 fv-plugins-icon-container">
    <small class="text-light fw-semibold">Choose Working Shift Type <span class="text-danger">*</span></small>
    <div class="form-check mt-3">
        <input name="type" class="form-check-input" type="radio" value="regular" id="regular" {{ $model->type=='regular'?'checked':'' }}/>
        <label class="form-check-label" for="regular"> Regular </label>
    </div>
    <div class="form-check">
        <input name="type" class="form-check-input" type="radio" value="scheduled" id="scheduled" {{ $model->type=='scheduled'?'checked':'' }} />
        <label class="form-check-label" for="scheduled"> Scheduled </label>
    </div>
    <span id="type_error" class="text-danger error"></span>
</div>
<div class="row">
    <label class="form-label">Set Regular Week <small>( Set week with fixed time )</small> </label>
    <div class="col-sm-6">
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="start_time">Start Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" name="start_time" id="start_time" value="{{ $model->start_time }}">
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="start_time_error" class="text-danger error"></span>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="end_time">End Time <span class="text-danger">*</span></label>
            <input type="time" class="form-control" name="end_time" id="end_time" value="{{ $model->end_time }}">
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="end_time_error" class="text-danger error"></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="mb-3 fv-plugins-icon-container">
            <label class="form-label" for="status">Status  </label>
            <select name="status" class="form-control" id="status">
                <option value="1" {{ $model->status==1?'selected':'' }}>Active</option>
                <option value="0" {{ $model->status==0?'selected':'' }}>In-Active</option>
            </select>
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="start_time_error error" class="text-danger"></span>
        </div>
    </div>
</div>
<div class="col-12 col-md-12">
    <label class="form-label" for="description">Description ( <small>Optional</small> )</label>
    <textarea class="form-control" name="description" id="description" placeholder="Enter description">{{ $model->description }}</textarea>
</div>

<script>
    CKEDITOR.replace('description');
    $('.form-select').select2();
</script>
