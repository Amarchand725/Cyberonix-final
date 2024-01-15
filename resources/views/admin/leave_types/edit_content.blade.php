<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="name">Title <span class="text-danger">*</span></label>
    <input type="text" class="form-control" value="{{ $model->name }}" id="name" placeholder="Enter leave type name" name="name">
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_error" class="text-danger error"></span>
</div>
<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="amount">Amount</label>
    <input type="number" class="form-control" value="{{ $model->amount }}" id="amount" step="0.01" placeholder="Enter leave type amount" name="amount">
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="amount_error" class="text-danger error"></span>
</div>
<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="spacial_percentage">Spacial Percentage</label>
    <input type="number" class="form-control" value="{{ $model->spacial_percentage }}" id="spacial_percentage" min="1" max="100" maxlength="3" placeholder="Enter spacial percentage" name="spacial_percentage">
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="spacial_percentage_error" class="text-danger error"></span>
</div>
<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
    <select name="type" id="type" class="form-control">
        <option value="" selected>Select Type</option>
        <option value="paid" {{ $model->type=='paid'?'selected':'' }}>Paid</option>
        <option value="unpaid" {{ $model->type=='unpaid'?'selected':'' }}>Un-paid</option>
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="type_error" class="text-danger error"></span>
</div>

<div class="mb-4">
    <label class="form-label" for="status">Select Status</label>
    <select id="status" name="status" class="form-control">
        <option value="" selected>Select status</option>
        <option value="1" {{ $model->status==1?'selected':'' }}>Active</option>
        <option value="0" {{ $model->status==0?'selected':'' }}>De-active</option>
    </select>
</div>
