<input type="hidden" name="asset_id" value="{{$model->asset->id ?? '' }}">
<div class="d-flex justify-content-between">
    <h5>{{$model->asset->name ?? '-'}} </h5>
    <div>
    <label for="">Available:</label>

    @if(!isset($model->assignee) || empty($model->assignee))
    <strong class="badge bg-label-success">YES</strong>
    @else
    <strong class="badge bg-label-danger">NO</strong>
    @endif
</div>
</div>
<hr>
<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Employee <span class="text-danger">*</span></label>
    <select name="employee_id" id="employee_id" class="form-control select2">
        <option value="">---Select---</option>
        @if(!empty($users))
        @foreach($users as $user)
        <option value="{{$user->id ?? ''}}" @if(!empty($model->id) && $model->id == $user->id ) selected @endif>
            {{!empty($user) ? getUserName($user) : '-'}} / ({{$user->profile->employment_id ?? ''}})
        </option>
        @endforeach
        @endif
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="employee_id_error" class="text-danger error"></span>
</div>
<div class="col-12 col-md-12 mb-3">
    <label class="form-label" for="title">Effective From <span class="text-danger">*</span></label>
    <input type="date" id="effective_date" name="effective_date" class="form-control" placeholder="Enter effective date" value="{{$date ?? ''}}" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="effective_date_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mb-2">
    <label class="form-label" for="title">Remarks </label>
    <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="3" placeholder="(optional)"></textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="remarks_error" class="text-danger error"></span>
</div>