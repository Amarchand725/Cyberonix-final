<hr>
<input type="hidden" id="unassign_asset_id" value="{{$asset->id ?? ''}}">
<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Mark as damage </label>
    <select name="is_damage" id="is_damage" class="form-control select2">
        <option value="2">No</option>
        <option value="1">Yes</option>
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="is_damage_error" class="text-danger error"></span>
</div>
<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Remarks </label>
    <textarea class="form-control" name="remarks" id="unassign_remarks" cols="30" rows="3" placeholder="(optional)"></textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="remarks_error" class="text-danger error"></span>
</div>