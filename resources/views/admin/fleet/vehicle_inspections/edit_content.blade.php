<div class="col-12 col-md-12">
    <label class="form-label" for="vehicle">Vehicle <span class="text-danger">*</span></label>
    <select class="form-select select2" id="vehicle" name="vehicle">
        <option value="" selected>Select Vehicle</option>
        @foreach($data['vehicles'] as $vehicle)
            <option value="{{ $vehicle->id }}" {{ $data['model']->vehicle_id==$vehicle->id?"selected":"" }}>{{ $vehicle->name }} ({{ $vehicle->color }}) - {{ $vehicle->registration_number }}</option>
        @endforeach
    </select>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="vehicle_error" class="text-danger error"></span>
</div>
<!--Recieve Date-->
<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="receive">Recieve Date <span class="text-danger">*</span></label>
    <input type="date" id="receive" name="receive" value="{{ $data['model']->receive_date }}" class="form-control" placeholder="Enter Recieve Date" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="receive_error" class="text-danger error"></span>
</div>
<!--Delivery Details-->
<div class="row mt-2">
    <div class="col-md-12">
        <label class="form-label" for="delivery_date">Delivery Date <span class="text-danger">*</span></label>
        <input type="date" id="delivery_date" name="delivery_date" value="{{ $data['model']->delivery_date }}" class="form-control" placeholder="Enter Delivery Date" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="delivery_date_error" class="text-danger error"></span>
    </div>
</div>

<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="delivery_details">Delivery Details </label>
    <textarea class="form-control" rows="5" name="delivery_details" id="delivery_details" placeholder="Enter Delivery Details">{{ $data['model']->delivery_details }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="delivery_details_error" class="text-danger error"></span>
</div>
<!--Inspection Details-->
<div class="col-12 col-md-12 mt-3">
    <label class="form-label" for="inspection_details">Inspection Details</label>
    <textarea class="form-control" rows="5" name="inspection_details" id="inspection_details" placeholder="Enter Inspection Detail">{{ $data['model']->inspection_details }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="inspection_details_error" class="text-danger error"></span>
</div>

<script>
    $('select').select2({
        dropdownParent: $('#offcanvasAddAnnouncement')
    });
</script>