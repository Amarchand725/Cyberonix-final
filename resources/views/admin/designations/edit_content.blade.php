<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
    <input type="text" class="form-control" id="title" value="{{ $model->title }}" placeholder="Enter position title" name="title">
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="title_error" class="text-danger"></span>
</div>
<div class="mb-3 fv-plugins-icon-container">
    <label class="form-label" for="description">Description</label>
    <textarea class="form-control" name="description" id="description" placeholder="Enter description">{!! $model->description !!}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="description_error" class="text-danger"></span>
</div>

<div class="mb-4">
    <label class="form-label" for="status">Status  </label>
    <select name="status" class="form-control" id="status">
        <option value="1" {{ $model->status==1?'selected':'' }}>Active</option>
        <option value="0" {{ $model->status==0?'selected':'' }}>In-Active</option>
    </select>
</div>

<script>
    CKEDITOR.replace('description');
    $('.form-select').select2();
</script>
