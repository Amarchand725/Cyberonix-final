@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    <input type="hidden" id="page_url" value="{{ route('insurances.index') }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                                <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card mt-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer pt-4">
                        <div class="container">
                            <h5 class="mb-4 fw-bold text-dark">INSURANCE INFORMATION</h5>
                            <form id="edit_insurance" class="row g-3" method="post" action="{{ route('insurances.store') }}" >
                                @csrf
                                <input type="hidden" name="user" value="user">
                                <div class="row">
                                    <div class="col-12 col-md-12 mt-2">
                                        <label class="form-label" for="name_as_per_cnic">Name as per CNIC <span class="text-danger">*</span></label>
                                        <input type="text" id="name_as_per_cnic" name="name_as_per_cnic" class="form-control" placeholder="Enter name as per cnic" />
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="name_as_per_cnic_error" class="text-danger error">{{ $errors->first('name_as_per_cnic') }}</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
                                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" />
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="date_of_birth_error" class="text-danger error">{{ $errors->first('date_of_birth') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="cnic_number">CNIC Number <span class="text-danger">*</span></label>
                                        <input type="text" value="" id="cnic_number" name="cnic_number" class="form-control cnic_number" placeholder="Enter CNIC Number here" />
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="cnic_number_error" class="text-danger error">{{ $errors->first('cnic_number') }}</span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="sex" name="sex">
                                            <option value="1">Male</option>
                                            <option value="0">Female</option>
                                            <option value="" selected>Select Sex</option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="sex_error" class="text-danger error">{{ $errors->first('sex') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                        <select class="form-select select2" id="marital_status" name="marital_status">
                                            <option value="1">Married</option>
                                            <option value="0">Single</option>
                                            <option value="" selected>Select marital Status</option>
                                        </select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="marital_status_error" class="text-danger error">{{ $errors->first('marital_status') }}</span>
                                    </div>
                                </div>
                                
                                <!--Relation Label-->
                                <div class="row mt-3 border-top py-3">
                                    <div class="col-12 d-flex justify-content-between align-items-center">
                                        <h5 class="card-title">Family Relations</h5>
                                        <div class="btn-wrapper">
                                            <button type="button" class="btn btn-label-primary btn-sm add-more-btn"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    
                                    <!--Relation and Name-->
                                    <span class="relation_data">
                                        <div class="row mt-2">
                                            <div class="col-md-6 mt-3">
                                                <label class="form-label" for="relationships">Relationship </label>
                                                <select class="form-control relationships" id="relationships" name="relationships[]"></select>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="relationships_error" class="text-danger error"></span>
                                            </div>
                                            <div class="col-md-6 mt-3">
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
                                    <span id="add-more-data"></span>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 mt-3 action-btn">
                                        <div class="demo-inline-spacing sub-btn">
                                            <button type="submit" class="btn btn-primary me-sm-3 me-1 insuranceBtn">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('public/admin/assets/js/custom/insurance.js') }}"></script>
@endpush
