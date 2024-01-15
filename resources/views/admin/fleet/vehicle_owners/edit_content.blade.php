<div class="col-md-12 mt-2">
    <label class="form-label" for="name">Company Name </label>
    <input type="text" id="company_name" name="company_name" value="{{ $model->company_name }}" class="form-control" placeholder="Enter company name" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="company_name_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" value="{{ $model->name }}" class="form-control" placeholder="Enter name" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="email">Email </label>
    <input type="email" id="email" name="email" value="{{ $model->email }}" class="form-control" placeholder="Enter email" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="email_error" class="text-danger error"></span>
</div>
<div class="col-md-12 mt-2">
    <label class="form-label" for="phone">Phone </label>
    <input type="text" id="phone" name="phone" value="{{ $model->phone }}" class="form-control phoneNumber" placeholder="Enter phone" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="phone_error" class="text-danger error"></span>
</div>

<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="address">Address </label>
    <textarea class="form-control" rows="5" name="address" id="address" placeholder="Enter address here....">{{ $model->address }}</textarea>
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="address_error" class="text-danger error"></span>
</div>
