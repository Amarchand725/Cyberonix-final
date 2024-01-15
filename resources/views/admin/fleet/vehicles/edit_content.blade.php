<input type="hidden" name="vehicle_id" value="{{ $data['model']->id }}" >
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="owner_id">Owner <span class="text-danger">*</span></label>
        <select id="owner_id" name="owner_id" class="form-control">
            <option value="">Select Owner </option>
            @foreach($data['vehicle_owners'] as $VehicleOwner)
                <option value="{{$VehicleOwner->id}}" {{ $data['model']->owner_id==$VehicleOwner->id?'selected':'' }}>{{$VehicleOwner->name}}</option>
            @endforeach
        </select>
        <span id="owner_id_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="name">Vehicle <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" value="{{ $data['model']->name }}" placeholder="Toyota" name="name">
        <span id="name_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="model_year">Model Year <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="model_year" value="{{ $data['model']->model_year }}" placeholder="2023" name="model_year">
        <span id="model_year_error" class="text-danger error"></span>
    </div>

</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="body_type">Body Type <span class="text-danger">*</span></label>
        <select id="body_type" name="body_type" class="form-control">
            <option value="">Select Body Type</option>
            @foreach($data['body_types'] as $body_type)
                <option value="{{ $body_type->id }}" {{ $data['model']->body_type==$body_type->id?"selected":"" }}>{{ $body_type->body_type }}</option>
            @endforeach
        </select>
        <span id="body_type_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="color">Color <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="color" value="{{ $data['model']->color }}" placeholder="Black" name="color">
        <span id="color_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="registration_number">Registeration Number <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="registration_number" value="{{ $data['model']->registration_number }}" placeholder="BNR 804" name="registration_number">
        <span id="registration_number_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="transmission">Transmission <span class="text-danger">*</span></label>
        <select id="transmission" name="transmission" class="form-control">
            <option value="">Select Transmission</option>
            <option value="Automatic" {{ $data['model']->transmission=="Automatic"?"selected":"" }}>Automatic</option>
            <option value="Manual" {{ $data['model']->transmission=="Manual"?"selected":"" }}>Manual</option>
        </select>
        <span id="transmission_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="engine_capacity">ENGINE CAPACITY (CC) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="engine_capacity" value="{{ $data['model']->engine_capacity }}" placeholder="1300" name="engine_capacity">
        <span id="engine_capacity_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="mileage">Mileage (KM) <span class="text-danger">*</span></label>
        <input type="number" class="form-control" id="mileage" value="{{ $data['model']->mileage }}" placeholder="60,000" name="mileage">
        <span id="mileage_error" class="text-danger error"></span>
    </div>
</div>
<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="registration_province">Registeration Province <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="registration_province" value="{{ $data['model']->registration_province }}" placeholder="Sindh" name="registration_province">
        <span id="registration_province_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="registration_city">Registeration City <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="registration_city" value="{{ $data['model']->registration_city }}" placeholder="Karachi" name="registration_city">
        <span id="registration_city_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="rent">Rent (PKR) <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="rent" @if(!empty($data['model']->hasRent)) value="{{ $data['model']->hasRent->rent }}" @endif placeholder="90,000" name="rent">
        <span id="rent_error" class="text-danger error"></span>
    </div>
    <div class="mb-3 fv-plugins-icon-container col-6">
        <label class="form-label" for="status">Status</label>
        <select id="status" name="status" class="form-control">
            <option value="">Select Status</option>
            <option value="1" {{ $data['model']->status=="1"?'selected':'' }}>Available</option>
            <option value="0" {{ $data['model']->status=="0"?'selected':'' }}>Not available</option>
        </select>
        <span id="status_error" class="text-danger error"></span>
    </div>
</div>

<div class="row">
    <div class="mb-3 fv-plugins-icon-container col-12">
        <label class="form-label" for="additional">Additional Detail (Optional)</label>
        <textarea rows="4" class="form-control" id="additional" placeholder="Additional Detail......" name="additional">{{ $data['model']->additional }}</textarea>
        <span id="additional_error" class="text-danger error"></span>
    </div>
</div>

 <div class="row upload__inputfile_div">
    <div class="mb-3 fv-plugins-icon-container col-12">
        <label class="form-label" for="thumbnail">Thumbnail </label>
        <div class="input_file_inner">
            <input type="file" name="thumbnail" class="form-control" id="thumbnail" accept="image/gif, image/jpeg, image/png">
        </div>
        @if(!empty($data['model']->thumbnail))
            <img id="preview-image" src="{{ asset('public/upload/vehicle/thumbnails') }}/{{ $data['model']->thumbnail }}" style="width:80px" class="mt-2" alt="Image Preview">
        @else
            <img id="preview-image" src="" style="display:none; width:80px" class="mt-2" alt="Image Preview">
        @endif
        <span id="thumbnail_error" class="text-danger error"></span>
    </div>
</div>

<div class="row upload__inputfile_div">
    <div class="mb-3 fv-plugins-icon-container col-12">
        <label class="form-label" for="images">Vehicle Images (Optional)</label>
        <div class="input_file_inner">
            <input type="file" multiple name="images[]" class="form-control fileUploadWithPreview" id="fileUpload" accept="image/gif, image/jpeg, image/jpg, image/png" data-id=1 >
        </div>
        @if(!empty($data['model']->hasImages) && sizeof($data['model']->hasImages) > 0)
            <div id="image-holder1" class="image-holder">
                @foreach($data['model']->hasImages as $image)
                    <div class="previewImage" id="id-{{ $image->id }}">
                        <img src="{{ asset('public/upload/vehicle/images') }}/{{ $image->image }}" class="thumb-image" /><i class="fa fa-times icon-close-vehicle remove-image-btn" data-id="{{ $image->id }}" data-remove-url="{{ route('vehicles.remove-image', $image->id)}}"></i>
                    </div>
                @endforeach
            </div>
        @else
            <div id="image-holder1" class="image-holder" style="display:none;"></div>
        @endif
    </div>
</div>

<div class="row upload__inputvideo_div">
    <div class="mb-3 fv-plugins-icon-container col-12">
        <label class="form-label" for="video">Vehicle Video (Optional)</label>
        <input id="file-input" type="file" class="form-control videoInputWithPreview" onchange="videoInputWithPreview('video1')" name="video" accept="video/*">
        @if(!empty($data['model']->video))
            <div class="video-holder">
                <div>
                    <div id="video1" >
                        <video class="video" controls>
                            <source src="{{ asset('public/upload/vehicle/video') }}/{{ $data['model']->video }}"></source>
                        </video>
                    </div>
                </div>
            </div>
        @else
            <div class="video-holder" style="display: none;">
                <div>
                    <div id="video1"></div>
                </div>
            </div>
        @endif
    </div>
</div>
