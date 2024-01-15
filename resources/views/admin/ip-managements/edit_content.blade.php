<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Ip Address <span class="text-danger">*</span></label>
    <input type="text" id="ip_address" name="ip_address" value="{{ $model->ip_address ?? '' }}" class="form-control" placeholder="Enter Ip Address" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="ip_address_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Status <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control select2">
        <option value="1" @if(isset($model->status) && !empty($model->status) && $model->status == 1) selected @endif> Allow </option>
        <option value="0" @if( $model->status == 0) selected @endif> Black List</option>
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="status_error" class="text-danger error"></span>
</div>

<script>
    CKEDITOR.replace('description');
    // $('.form-select').select2();
</script>
