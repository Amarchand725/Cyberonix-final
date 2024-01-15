<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Name <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" value="{{ $model->name ?? '' }}" class="form-control" placeholder="Enter Category Name" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12">
    <label class="form-label" for="title">Status <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control select2">
        <option value="1" @if(isset($model->status) && !empty($model->status) && $model->status == 1) selected @endif> Active</option>
        <option value="0" @if(empty($model->status) || $model->status == null || $model->status == 0) selected @endif>In-Active</option>
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="status_error" class="text-danger error"></span>
</div>

<script>
    CKEDITOR.replace('description');
    $('.form-select').select2();
</script>