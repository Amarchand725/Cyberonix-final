<div class="col-md-12 mt-2">
    <label class="form-label" for="user">User <span class="text-danger">*</span></label>
    <select name="user" id="user" class="form-control">
        <option value="" selected>Select User</option>
        @foreach ($data['users'] as $user)
            <option value="{{ $user->id }}" {{ $data['model']->user_id==$user->id?'selected':'' }}>{{ $user->first_name }} {{ $user->last_name }}</option>
        @endforeach
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="vehicle_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="user_cnic">User CNIC <span class="text-danger">*</span></label>
    <input type="text" name="user_cnic" id="user_cnic" class="form-control cnic_number" value="{{ $data['user_cnic'] }}" placeholder="Enter User CNIC" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="user_cnic_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="vehicle">Vehicle <span class="text-danger">*</span></label>
    <select name="vehicle" id="vehicle" class="form-control">
        <option value="" selected>Select Vehicle</option>
        @foreach ($data['available_vehicles'] as $vehicle)
            <option value="{{ $vehicle->id }}" {{ $data['model']->vehicle_id==$vehicle->id?'selected':'' }}>{{ $vehicle->name }} ({{ $vehicle->color }})</option>
        @endforeach
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="vehicle_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="deliver">Deliver <span class="text-danger">*</span></label>
    <input type="date" id="deliver" name="deliver" value="{{ $data['model']->deliver_date }}" class="form-control" placeholder="Enter deliver" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="deliver_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="note">Note</label>
    <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note here....">{{ $data['model']->note }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="note_error" class="text-danger error"></span>
</div>
