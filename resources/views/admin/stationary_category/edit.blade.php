<div class="col-12 col-md-12">
    <label class="form-label" for="stationary_category">Stationary Category<span class="text-danger">*</span></label>
    <input type="text" name="stationary_category" id="stationary_category" value="{{ $model->stationary_category }}" class="form-control" placeholder="Enter Stationary Category" />
</div>

<div class="col-12 col-md-12">
    <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-control" id="status">
        <option value="" {{ $model->status==""?'selected':'' }}>Select class</option>
        <option value="1" {{ $model->status=="1"?'selected':'' }}> Active </option>
        <option value="0" {{ $model->status=="0"?'selected':'' }}> In-Active </option>
    </select>
</div>