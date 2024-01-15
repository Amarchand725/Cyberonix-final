{{--  @if (in_array("Admin", Auth::user()->roles->pluck('name')->toArray()))  --}}
<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">User <span class="text-danger">*</span></label>
    <select name="user_id" id="user_id" class="form-control select2">
        <option value=""> select </option>
        @if(isset($users) && count($users) > 0)
        @foreach ($users as $user)
        <option value="{{ $user->id }}" @if(isset($model->user_id) && !empty($model->user_id) && $model->user_id == $user->id) selected @endif>{{ getUserName($user) }}</option>
        @endforeach
        @endif
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="user_id_error" class="text-danger error"></span>
</div>
{{--  @else
<input type="hidden" value="{{ Auth::user()->id }}" name="user_id" id="user_id">
@endif  --}}
<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Description <span class="text-danger">*</span></label>
    <textarea name="description" id="description" cols="30" class="form-control" placeholder="Description" rows="10">{{ $model->description ?? '' }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="description_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Anonymous <span class="text-danger">*</span></label>
    <select name="anonymous" id="anonymous" class="form-control select2">
        <option value="">Select</option>
        <option value="0" @if($model->anonymous == 0) selected @endif> Yes </option>
        <option value="1" @if(isset($model->anonymous) && !empty($model->anonymous) && $model->anonymous == 1) selected @endif> No </option>
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="anonymous_error" class="text-danger error"></span>
</div>

{{--  <div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Status <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-control select2">
        <option value="">Select</option>
        <option value="1" @if(isset($model->status) && !empty($model->status) && $model->status == 1) selected @endif> Active </option>
        <option value="0" @if( $model->status == 0) selected @endif> Deactive</option>
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="status_error" class="text-danger error"></span>
</div>  --}}

<script>
    CKEDITOR.replace('description');
    $('.form-select').select2();
</script>
