<div class="col-md-12 mt-2">
    <label class="form-label" for="vehicle">Vehicle <span class="text-danger">*</span></label>
    <select name="vehicle" id="vehicle" class="form-select select2">
        <option value="" selected>Select Vehicle</option>
        @foreach ($data['vehicles'] as $vehicle)
            <option value="{{ $vehicle->id }}" {{ $data['model']->vehicle_id==$vehicle->id?"selected":"" }}>{{ $vehicle->name }} ({{ $vehicle->color }})</option>
        @endforeach
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="vehicle_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="rent">Rent (PKR) <span class="text-danger">*</span></label>
    <input type="number" id="rent" name="rent" value="{{ $data['model']->rent }}" class="form-control"/>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="rent_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="effective_date">Effective Date <span class="text-danger">*</span></label>
    <input type="date" id="effective_date" name="effective_date" value="{{ $data['model']->effective_date }}" class="form-control"/>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="effective_date_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="note">Note</label>
    <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note here....">{{ $data['model']->note }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="note_error" class="text-danger error"></span>
</div>
<script>
    $('select').select2();
</script>