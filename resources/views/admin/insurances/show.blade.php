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
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end align-item-center mt-4">
                            <div class="dt-buttons btn-group flex-wrap">
                                @if(empty($model))
                                    @can('insurances-create')
                                        <button
                                            class="btn btn-secondary add-new btn-primary mx-3"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Add Insurance Details"
                                            id="add-btn"
                                            data-url="{{ route('insurances.store') }}"
                                            tabindex="0" aria-controls="DataTables_Table_0"
                                            type="button" data-bs-toggle="modal"
                                            data-bs-target="#create-form-modal"
                                            >
                                            <span>
                                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">Add Insurance Details</span>
                                            </span>
                                        </button>
                                    @endcan
                                @else
                                    @can('insurances-edit')
                                        <button
                                            class="btn btn-secondary btn-primary mx-3 edit-btn"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Edit Insurance Details"
                                            data-edit-url="{{ route('insurances.edit', $model->id) }}"
                                            data-url="{{ route('insurances.update', $model->id) }}"
                                            type="button"
                                            tabindex="0" aria-controls="DataTables_Table_0"
                                            type="button" data-bs-toggle="modal"
                                            data-bs-target="#create-form-modal"
                                            >
                                            <span>
                                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">Edit Insurance Details</span>
                                            </span>
                                        </button>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card mt-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer pt-4">
                        <div class="container">
                            <h5 class="mb-4 fw-bold text-dark">PERSONAL INFORMATION</h5>
                            <table class="dt-row-grouping table dataTable dtr-column table-border">
                                @if(empty($model))
                                    <tr>
                                        <td colspan="2">
                                            <h5 class="text-center m-0">No Data Found</h5>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <th>Employee</th>
                                        <td>
                                            <div class="d-flex justify-content-start align-items-center user-name">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3">
                                                        @if(!empty($model->hasUser->profile->image))
                                                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasUser->profile->image, null) }}" alt="Avatar" class="rounded-circle">
                                                        @else
                                                            <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('employees.show', $model->hasUser->slug) }}" class="text-body text-truncate">
                                                        <span class="fw-semibold">{{ Str::ucfirst($model->hasUser->first_name??'') }} {{ Str::ucfirst($model->hasUser->last_name??'') }}</span>
                                                    </a>
                                                    <small class="text-muted">{{ $model->hasUser->email??'-' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Name as per CNIC</th>
                                        <td>{{ $model->name_as_per_cnic }}</td>
                                    </tr>
                                    <tr>
                                        <th>CNIC Number</th>
                                        <td>{{ $model->cnic_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date of Birth</th>
                                        <td>
                                            @if(!empty($model->date_of_birth))
                                                {{ date('d M Y', strtotime($model->date_of_birth)) }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Sex</th>
                                        <td>
                                            @if($model->sex==1)
                                                <span class="badge bg-label-success" text-capitalized="">Male</span>
                                            @else
                                                <span class="badge bg-label-danger" text-capitalized="">Female</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Marital Status</th>
                                        <td>
                                            @if($model->marital_status==1)
                                                <span class="badge bg-label-success" text-capitalized="">Married</span>
                                            @else
                                                <span class="badge bg-label-danger" text-capitalized="">Single</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td>
                                            @if(!empty($model->created_at))
                                                {{ date('d M Y  h:i A', strtotime($model->created_at)) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            </table>
                            @if(!empty($model) && isset($model->hasInsuranceMeta) && !empty($model->hasInsuranceMeta) && sizeof($model->hasInsuranceMeta) > 0)
                                <table class="dt-row-grouping table dataTable dtr-column table-border">
                                    <h5 class="mb-4 mt-5 fw-bold text-dark">FAMILY REALTIONS</h5>
                                    <thead>
                                        <tr>
                                            <th>Relation</th>
                                            <th>Name</th>
                                            <th>Date of Birth</th>
                                            <th>CNIC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($model->hasInsuranceMeta as $insurance_meta)
                                            <tr>
                                                <td>{{ Str::ucfirst($insurance_meta->relationship) }}</td>
                                                <td>{{ Str::ucfirst($insurance_meta->name) }}</td>
                                                <td>
                                                    @if(!empty($insurance_meta->date_of_birth))
                                                        {{ date('d M Y', strtotime($insurance_meta->date_of_birth)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $insurance_meta->cnic_number??'-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employment Status Modal -->
    <div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top modal-simple">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form id="create-form" class="row g-3" data-method="" data-modal-id="create-form-modal">
                        @csrf

                        <span id="edit-content">
                            <div class="col-12 col-md-12 mt-2">
                                <label class="form-label" for="name_as_per_cnic">Name as per CNIC <span class="text-danger">*</span></label>
                                <input type="text" id="name_as_per_cnic" name="name_as_per_cnic" class="form-control" placeholder="Enter name as per cnic" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="name_as_per_cnic_error" class="text-danger error"></span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label" for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="date_of_birth_error" class="text-danger error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="cnic_number">CNIC Number <span class="text-danger">*</span></label>
                                    <input type="text" value="" id="cnic_number" name="cnic_number" class="form-control cnic_number" placeholder="Enter CNIC Number here" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="cnic_number_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="sex" name="sex">
                                        <option value="" selected>Select Sex</option>
                                        <option value="1">Male</option>
                                        <option value="0">Female</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="sex_error" class="text-danger error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="marital_status" name="marital_status">
                                        <option value="" selected>Select marital Status</option>
                                        <option value="1">Married</option>
                                        <option value="0">Single</option>
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
                        </span>

                        <div class="col-12 mt-3 action-btn">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                            <div class="demo-inline-spacing loading-btn" style="display: none;">
                                <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                  <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                  Loading...
                                </button>
                                <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--/ Edit Employment Status Modal -->
@endsection
@push('js')
    <script src="{{ asset('public/admin/assets/js/custom/insurance.js') }}"></script>
@endpush
