<div class="col-12 col-md-12">
    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
    <input type="text" id="title" name="title" class="form-control" value="{{ $model->title }}" placeholder="Enter title" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="title_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="description">Template <span class="text-danger">*</span></label>
    <textarea class="form-control" name="description" id="description" placeholder="Enter template">{!! $model->template !!}</textarea>

    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="description_error" class="text-danger error"></span>
</div>


<script>
    CKEDITOR.replace('description');
    $('.form-select').select2();
</script>
