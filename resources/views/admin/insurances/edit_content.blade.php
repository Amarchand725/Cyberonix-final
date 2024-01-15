@if(Auth::user()->hasRole('Admin'))
    <div class="col-12 col-md-12">
        <label class="form-label" for="user_id">Employee <span class="text-danger">*</span></label>
        <select class="form-select select2" id="user_id" name="user_id">
            <option value="" selected>Select Employee</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}" {{ $model->user_id==$employee->id?"selected":"" }}>{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id }})</option>
            @endforeach
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="user_id_error" class="text-danger error"></span>
    </div>
@endif
<div class="col-12 col-md-12 mt-2">
    <label class="form-label" for="name_as_per_cnic">Name as per CNIC <span class="text-danger">*</span></label>
    <input type="text" id="name_as_per_cnic" name="name_as_per_cnic" value="{{ $model->name_as_per_cnic }}" class="form-control" placeholder="Enter name as per cnic" />
    <div class="fv-plugins-message-container invalid-feedback"></div>
    <span id="name_as_per_cnic_error" class="text-danger error"></span>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <label class="form-label" for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ $model->date_of_birth }}" class="form-control" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="date_of_birth_error" class="text-danger error"></span>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="cnic_number">CNIC Number <span class="text-danger">*</span></label>
        <input type="text" id="cnic_number" name="cnic_number" value="{{ $model->cnic_number }}" class="form-control cnic_number" placeholder="Enter CNIC Number here" />
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="cnic_number_error" class="text-danger error"></span>
    </div>
</div>
<div class="row mt-2">
    <div class="col-md-6">
        <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
        <select class="form-select select2" id="sex" name="sex">
            <option value="" selected>Select Sex</option>
            <option value="1" {{ $model->sex==1?"selected":"" }}>Male</option>
            <option value="0" {{ $model->sex==0?"selected":"" }}>Female</option>
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="sex_error" class="text-danger error"></span>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="marital_status">Marital Status <span class="text-danger">*</span></label>
        <select class="form-select select2" id="marital_status" name="marital_status">
            <option value="" selected>Select marital Status</option>
            <option value="1" {{ $model->marital_status==1?"selected":"" }}>Married</option>
            <option value="0" {{ $model->marital_status==0?"selected":"" }}>Single</option>
        </select>
        <div class="fv-plugins-message-container invalid-feedback"></div>
        <span id="marital_status_error" class="text-danger error"></span>
    </div>
</div>

<!--Relation Label-->
<div class="row mt-3 border-top py-3">
    <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-title">Family Relations</h5>
        <div class="btn-wrapper">
            <button type="button" class="btn btn-label-primary btn-sm add-more-btn"><i class="fa fa-plus"></i></button>
        </div>
    </div>
</div>
<!--Relation and Name-->
<span class="relation_data">
    <div class="row mt-2 w-full">
        <div class="col-md-6">
            <label class="form-label" for="relationships">Relationship </label>
            <select class="form-control relationships" id="relationships" name="relationships[]">
                <option value="" selected>Select relation</option>
                @if($model->marital_status==1)
                    <option value="husband">Husband</option>
                    <option value="wife">Wife</option>
                    <option value="son">Son</option>
                    <option value="daughter">Daughter</option>
                @else
                    <option value="father">Father</option>
                    <option value="mother">Mother</option>
                @endif
            </select>
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="relationships_error" class="text-danger error"></span>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="family_rel_names">Name </label>
            <input type="text" id="family_rel_names" name="family_rel_names[]" class="form-control" placeholder="Enter name" />
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="family_rel_names_error" class="text-danger error"></span>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">
            <label class="form-label" for="family_rel_dobs">Date of birth </label>
            <input type="date" id="family_rel_dobs" name="family_rel_dobs[]" class="form-control" />
            <div class="fv-plugins-message-container invalid-feedback"></div>
            <span id="family_rel_dobs_error" class="text-danger error"></span>
        </div>
       <div class="col-md-6 cnic"></div>
    </div>
</span>
<span id="add-more-data">
    @if(isset($model->hasInsuranceMeta) && !empty($model->hasInsuranceMeta) && sizeof($model->hasInsuranceMeta) > 0)
        @foreach($model->hasInsuranceMeta as $insurance_meta)
            <span class="relation_data">
                <div class="row mt-4 w-full border-top py-2 position-relative">
                    <div class="close-btn-wrapper position-absolute d-flex flex-row-reverse mb-3">
                        <button type="button" class="btn btn-label-primary btn-sm btn-relation-close"><i class="fa fa-close icon-close"></i></button>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="relationships">Relationship </label>
                        <select class="form-control relationships" id="relationships" name="relationships[]">
                            <option value="" selected>Select relation</option>
                            @if($model->marital_status==1)
                                <option value="husband" {{ $insurance_meta->relationship=='husband'?"selected":"" }}>Husband</option>
                                <option value="wife" {{ $insurance_meta->relationship=='wife'?"selected":"" }}>Wife</option>
                                <option value="son" {{ $insurance_meta->relationship=='son'?"selected":"" }}>Son</option>
                                <option value="daughter" {{ $insurance_meta->relationship=='daughter'?"selected":"" }}>Daughter</option>
                            @else
                                <option value="father" {{ $insurance_meta->relationship=='father'?"selected":"" }}>Father</option>
                                <option value="mother" {{ $insurance_meta->relationship=='mother'?"selected":"" }}>Mother</option>
                            @endif
                        </select>
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="relationships_error" class="text-danger error"></span>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="family_rel_names">Name </label>
                        <input type="text" id="family_rel_names" name="family_rel_names[]" value="{{ $insurance_meta->name }}" class="form-control" placeholder="Enter name" />
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="family_rel_names_error" class="text-danger error"></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label" for="family_rel_dobs">Date of birth </label>
                        <input type="date" id="family_rel_dobs" name="family_rel_dobs[]" value="{{ $insurance_meta->date_of_birth }}" class="form-control" />
                        <div class="fv-plugins-message-container invalid-feedback"></div>
                        <span id="family_rel_dobs_error" class="text-danger error"></span>
                    </div>
                    <div class="col-md-6 cnic">
                        @if($insurance_meta->relationship=='father')
                            <label class="form-label" for="father_cnic_number">CNIC <span class="text-danger">*</span></label>
                            <input type="text" id="father_cnic_number" name="father_cnic_number" value="{{ $insurance_meta->cnic_number }}" class="form-control cnic_number" placeholder="Enter father cnic number "/>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="father_cnic_number_error" class="text-danger error"></span>
                        @endif
                    </div>
                </div>
            </span>
        @endforeach
    @endif
</span>
