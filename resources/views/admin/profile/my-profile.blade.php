@extends('admin.layouts.app')
@section('title', $title . ' - ' . appName())
@section('content')
<div class="content-wrapper">
    <!-- Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">User /</span> <span class="text-muted fw-light">View /</span> Profile</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <div class="card mb-4 h-100">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                @if (isset($model->profile) && !empty($model->profile->profile))
                                <img class="object-fit-cover rounded mb-3 pt-1 mt-4" src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->profile->profile, null) }}" height="100" width="100" alt="User avatar" />
                                @else
                                <img class="object-fit-cover rounded mb-3 pt-1 mt-4" src="{{ asset('public/admin/default.png') }}" height="100" width="100" alt="User avatar" />
                                @endif
                                <div class="user-info text-center">
                                    <h4>{{ $model->first_name }} {{ $model->last_name }} <span data-toggle="tooltip" data-placement="top" title="Employment ID">(
                                            {{ $model->profile->employment_id ?? '-' }} )</span></h4>
                                    <span class="badge bg-label-secondary mt-1">
                                        @if (isset($model->jobHistory->designation->title) && !empty($model->jobHistory->designation->title))
                                        {{ $model->jobHistory->designation->title }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
                            <div class="d-flex align-items-start me-4 mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-calendar ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">
                                        {{ date('d F Y', strtotime($joining_date->joining_date ?? '-')) }}
                                    </p>
                                    <small>Joining Date</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-id ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">
                                        {{ $model->profile->employment_id ?? '-' }}
                                    </p>
                                    <small>ID</small>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 small text-uppercase text-muted">Details</p>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">Employee:</span>
                                    <span>
                                        {{ $model->first_name }} {{ $model->last_name }}
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">CNIC:</span>
                                    <span>
                                        @if(!empty($model->profile))
                                            {{ $model->profile->cnic??'N/A' }}
                                        @else
                                            {{ '-' }}
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Email:</span>
                                    <span>{{ $model->email ?? '-' }}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">Department:</span>
                                    <span>
                                        @if (isset($model->departmentBridge->department) && !empty($model->departmentBridge->department->name))
                                        {{ $model->departmentBridge->department->name }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">Timing:</span>
                                    <span>
                                        @if (isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name))
                                        {{ $model->userWorkingShift->workShift->name }}
                                        @else
                                        @if (isset($model->departmentBridge->department->departmentWorkShift->workShift) &&
                                        !empty($model->departmentBridge->department->departmentWorkShift->workShift->name))
                                        {{ $model->departmentBridge->department->departmentWorkShift->workShift->name }}
                                        @else
                                        -
                                        @endif
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Role:</span>
                                    <span>
                                        @if (!empty($model->getRoleNames()))
                                        @foreach ($model->getRoleNames() as $roleName)
                                        {{ $roleName }},
                                        @endforeach
                                        @else
                                        -
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">Employment Status:</span>
                                    <span>
                                        @if (isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name))
                                        <span class="badge bg-label-{{ $model->employeeStatus->employmentStatus->class }}">
                                            {{ $model->employeeStatus->employmentStatus->name }}</span>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Status:</span>
                                    @if ($model->status)
                                    <span class="badge bg-label-success">Active</span>
                                    @else
                                    <span class="badge bg-label-danger">In-Active</span>
                                    @endif
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Phone:</span>
                                    <span>
                                        @if (isset($model->profile) && !empty($model->profile->phone_number))
                                        {{ $model->profile->phone_number }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Gender:</span>
                                    <span>
                                        @if (isset($model->profile) && !empty($model->profile->gender))
                                        {{ Str::ucfirst($model->profile->gender) }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">Birth Day:</span>
                                    <span>
                                        @if (isset($model->profile) && !empty($model->profile->date_of_birth))
                                        {{ date('d M Y', strtotime($model->profile->date_of_birth)) }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills flex-column flex-md-row mb-4 profile-tabs">
                    <li class="nav-item"></li>
                    <li class="nav-item">
                        <button type="button" class="nav-link active " role="tab" data-bs-toggle="tab" data-bs-target="#navs-edit-account" aria-controls="navs-edit-account" aria-selected="true">
                            <i class="ti ti-user-check me-1 ti-xs"></i>Edit Account
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-password" aria-controls="navs-password" aria-selected="true"><i class="ti ti-lock me-1 ti-xs"></i>Password</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-address" aria-controls="navs-address" aria-selected="true"><i class="ti ti-building me-1 ti-xs"></i>Addess</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-emergency-contact" aria-controls="navs-emergency-contact" aria-selected="true">
                            <i class="ti ti-cell me-1 ti-xs"></i>Emergency Contact
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-job-history" aria-controls="navs-job-history" aria-selected="true"><i class="ti ti-tag me-1 ti-xs"></i>Job History</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#user-documents" aria-controls="user-documents" aria-selected="true">
                            <i class="ti ti-file me-1 ti-xs"></i>Documents</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link " role="tab" data-bs-toggle="tab" data-bs-target="#user-assets" aria-controls="user-assets" aria-selected="true">
                            <i class="ti ti-asset me-1 ti-xs"></i>Assets</button>
                    </li>
                    {{-- <li class="nav-item">
                        <button type="button" class="nav-link read-all-notifications" role="tab" data-bs-toggle="tab" data-bs-target="#navs-notification" aria-controls="navs-notification" aria-selected="true">
                            <i class="ti ti-bell me-1 ti-xs"></i>Notifications <span class="badge bg-danger rounded-pill read-badge-notification">{{ count(auth()->user()->unreadnotifications) }}</span>
                    </button>
                    </li> --}}
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="navs-edit-account" role="tabpanel">
                        <div class="card-body">
                            <form id="edit-profile" class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" action="{{ route('profile.update', $model->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf {{ method_field('PATCH') }}

                                <div class="row">
                                    <div class="col-xl-6 order-1 order-xl-0">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="first_name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" placeholder="Enter first name" value="{{ $model->first_name }}" name="first_name" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="first_name_error" class="text-danger error"></span>
                                        </div>

                                        <div class="mb-3">
                                            <label class="d-block form-label">Gender</label>
                                            <div class="form-check form-check-inline mt-3">
                                                <input type="radio" id="gender-male" name="gender" class="form-check-input" @if (isset($model->profile) && !empty($model->profile->gender) && $model->profile->gender == 'male') checked @endif required
                                                value="male" />
                                                <label class="form-check-label" for="gender-male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline mt-3">
                                                <input type="radio" id="gender-female" name="gender" class="form-check-input" @if (isset($model->profile) && !empty($model->profile->gender) && $model->profile->gender == 'female') checked @endif required
                                                value="female" />
                                                <label class="form-check-label" for="gender-female">Female</label>
                                            </div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="gender_error" class="text-danger error"></span>
                                        </div>

                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="phone_number">Mobile </label>
                                            <input type="text" class="form-control mobileNumber" id="phone_number" placeholder="Enter your phone number" value="{{ $model->profile->phone_number ?? '' }}" name="phone_number" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="phone_number_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 order-0 order-xl-0">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" placeholder="Enter last name" value="{{ $model->last_name }}" name="last_name" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="last_name_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="date_of_birth">Date of birth </label>
                                            <input type="date" class="form-control" id="date_of_birth" placeholder="Enter your contact number" value="{{ $model->profile->date_of_birth ?? '' }}" name="date_of_birth" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="date_of_birth_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="marital_status">Marital Status </label>
                                            <select name="marital_status" id="marital_status" class="form-control">
                                                <option value="" selected>Select marital status</option>
                                                <option value="1" {{ isset($model->profile->marital_status) && $model->profile->marital_status == 1 ? 'selected' : '' }}>
                                                    Married</option>
                                                <option value="0" {{ isset($model->profile->marital_status) && $model->profile->marital_status == 0 ? 'selected' : '' }}>
                                                    Single</option>
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="marital_status_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="profile">Upload Profile Image</label>
                                            <input type="file" class="form-control" accept="image/*" id="profile" placeholder="Enter first name" name="profile" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="profile_error" class="text-danger error"></span>
                                            @if (isset($model->profile) && !empty($model->profile->profile))
                                            <span id="cnic_front-preview">
                                                <img style="max-width: 85px !important; max-height: 85px !important;" src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->profile->profile, null) }}" alt="CNIC-Front" />
                                            </span>
                                            @else
                                            <span id="profile-preview"></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="cnic">CNIC</label>
                                            <input type="text" class="form-control cnic_number" value="{{ $model->profile->cnic }}" id="cnic_number" placeholder="Enter cnic number" name="cnic" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="cnic_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="cnic_front">Upload CNIC Front</label>
                                            <input type="file" class="form-control" accept="image/*" id="cnic_front" placeholder="Enter first name" name="cnic_front" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="cnic_front_error" class="text-danger error"></span>

                                            @if (isset($model->profile) && !empty($model->profile->cnic_front))
                                            <span id="cnic_front-preview">
                                                <img style="width:50%; height:100px" src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->profile->cnic_front }}" alt="CNIC-Front" />
                                            </span>
                                            @else
                                            <span id="cnic_front-preview"></span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="cnic_back">Upload CNIC Back</label>
                                            <input type="file" class="form-control" accept="image/*" id="cnic_back" placeholder="Enter first name" name="cnic_back" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="cnic_back_error" class="text-danger error"></span>

                                            @if (isset($model->profile) && !empty($model->profile->cnic_back))
                                            <span id="cnic_back-preview">
                                                <img style="width:50%; height:100px" src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->profile->cnic_back }}" alt="CNIC-Back" />
                                            </span>
                                            @else
                                            <span id="cnic_back-preview"></span>
                                            @endif
                                        </div>
                                    </div>
                                    @if (count($cover_images) > 0)
                                    <div class="col-xl-12 order-0 order-xl-0">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="cover_image_id">Cover Image</label>
                                            <div class="row gy-3">
                                                @foreach ($cover_images as $cover_image)
                                                <div class="col-md-3">
                                                    <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label custom-option-content" for="cover_image_id{{ $cover_image->id }}">
                                                            <span class="custom-option-body">
                                                                <img style="width: 150px; height: 50px;" class="img-fluid" src="{{ asset('public/admin/assets/img/pages') }}/{{ $cover_image->image }}" alt="Cover Profile Image" />
                                                            </span>
                                                            @if (isset($model->profile) &&
                                                            !empty($model->profile->cover_image_id) &&
                                                            $model->profile->cover_image_id == $cover_image->id)
                                                            <input name="cover_image_id" class="form-check-input" type="radio" value="{{ $cover_image->id }}" id="cover_image_id{{ $cover_image->id }}" checked />
                                                            @else
                                                            <input name="cover_image_id" class="form-check-input" type="radio" value="{{ $cover_image->id }}" id="cover_image_id{{ $cover_image->id }}" />
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-xl-12 order-0 order-xl-0">
                                        <div class="mb-3 fv-plugins-icon-container">
                                            <label class="form-label" for="about_me">About </label>
                                            <textarea name="about_me" id="about_me" cols="30" rows="5" class="form-control" placeholder="Enter about you.">{{ $model->profile->about_me ?? '' }}</textarea>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="about_me_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="col-12 order-2 order-xl-0">
                                        <button type="button" class="btn btn-primary me-2 d-none" id="editButton">Edit</button>
                                        <button id="updateButton" type="submit" class="btn btn-primary me-2">
                                            Update
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-password" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 pl-md-2 pt-md-0 pt-sm-4 pt-4">
                                    <div class="tab-content px-primary">
                                        <div id="Change Password-1" class="tab-pane fade active show">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="d-flex align-items-center text-capitalize mb-0 title tab-content-header">
                                                    Change Password
                                                </h5>
                                                <div class="d-flex align-items-center mb-0"></div>
                                            </div>
                                            <hr />
                                            <div class="content py-primary" id="change-password">
                                                <div class="content" id="Change Password-1">
                                                    <form id="create-form" data-modal-id="change-password" action="{{ route('profile.change-password') }}" data-method="POST">
                                                        @csrf

                                                        <div class="form-group" placeholder="Enter old password" show-password="true">
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                                                                    <label class="text-left d-block mb-lg-0">
                                                                        Old password <span class="text-danger">*</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-8 col-xl-8 col-md-8 col-sm-12">
                                                                    <div class="form-password-toggle">
                                                                        <div class="input-group">
                                                                            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="············" aria-describedby="basic-default-password2" />
                                                                            <span id="old_password" class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                                                            <div class="fv-plugins-message-container invalid-feedback">
                                                                            </div>
                                                                            <span id="old_password_error" class="text-danger error"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-2">
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-xl-3">
                                                                        <label for="input-text-new-password" class="text-left d-block mb-2 mb-lg-0">
                                                                            New password <span class="text-danger">*</span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="col-lg-8 col-xl-8">
                                                                        <div class="form-password-toggle">
                                                                            <div class="input-group">
                                                                                <input type="password" class="form-control" id="password" name="password" placeholder="············" aria-describedby="basic-default-password2" />
                                                                                <span id="password" class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                                                                <div class="fv-plugins-message-container invalid-feedback">
                                                                                </div>
                                                                                <span id="password_error" class="text-danger error"></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-2">
                                                            <div class="row align-items-center">
                                                                <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                                                                    <label class="text-left d-block mb-lg-0">
                                                                        Confirm password <span class="text-danger">*</span>
                                                                    </label>
                                                                </div>
                                                                <div class="col-lg-8 col-xl-8 col-md-8 col-sm-12">
                                                                    <div class="form-password-toggle">
                                                                        <div class="input-group">
                                                                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="············" aria-describedby="basic-default-password2" />
                                                                            <span id="password_confirmation" class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                                                            <div class="fv-plugins-message-container invalid-feedback">
                                                                            </div>
                                                                            <span id="password_confirmation_error" class="text-danger error"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mt-5 mb-0">
                                                            <button data-v-27baa82a="" type="submit" class="btn text-center btn-primary applyDiscrepancyLeaveBtn">
                                                                <span data-v-27baa82a="" class="w-100">
                                                                    Save
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-address" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 order-1 order-xl-0">
                                    <h4>Address Details</h4>
                                    <hr />
                                    <div class="content py-primary">
                                        @if (isset($user_permanent_address) && !empty($user_permanent_address))
                                        @php $permanent_address = json_decode($user_permanent_address['value']); @endphp

                                        <div class="col-12" id="id-{{ $user_permanent_address->id }}">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <h4 class="mb-2">Permanent Address</h4>
                                                        <dl class="row mt-4 mb-0">
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Address:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $permanent_address->details ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">City:
                                                            </dt>
                                                            <dd class="col-sm-8">
                                                                {{ $permanent_address->city ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Area:
                                                            </dt>
                                                            <dd class="col-sm-8">
                                                                {{ $permanent_address->area ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                State:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $permanent_address->state ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Zip
                                                                Code:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $permanent_address->zip_code ?? '' }}
                                                            </dd>
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Country:</dt>
                                                            <dd class="col-sm-8">
                                                                <span class="text-capitalize">{{ $permanent_address->country ?? '' }}</span>
                                                            </dd>
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Mobile Number:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $permanent_address->phone_number ?? '' }}
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="d-flex flex-column text-start text-lg-end data-{{ $user_permanent_address->id }}">
                                                        <div class="d-flex order-sm-0 order-1">
                                                            <button class="btn btn-label-primary me-3 waves-effect edit-btn" data-toggle="tooltip" data-placement="top" data-edit-url="{{ route('user_contacts.edit', $user_permanent_address->id) }}" data-url="{{ route('user_contacts.update', $user_permanent_address->id) }}" title="Edit Permanent Address" data-type="permanent_address" type="button" data-bs-toggle="modal" data-bs-target="#addNewAddress">
                                                                Edit
                                                            </button>
                                                            <button class="btn btn-label-secondary waves-effect delete-address" data-toggle="tooltip" data-placement="top" title="Delete" data-type="permanent_address" data-slug="{{ $user_permanent_address->id }}" data-del-url="{{ route('user_contacts.destroy', $user_permanent_address->id) }}">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-12">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <h4 class="mb-2">Permanent Address</h4>
                                                    </div>
                                                    <div class="d-flex flex-column text-start text-lg-end">
                                                        <div class="d-flex order-sm-0 order-1">
                                                            <button title="Add Permanent Address" data-type="permanent_address" data-url="{{ route('user_contacts.store') }}" class="btn btn-primary btn-sm add-btn waves-effect waves-light custom-btn" type="button" data-bs-toggle="modal" data-bs-target="#addNewAddress">
                                                                Add Address
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if (isset($user_current_address) && !empty($user_current_address))
                                        @php $current_address = json_decode($user_current_address['value']); @endphp
                                        <div class="col-12" id="id-{{ $user_current_address->id }}">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <h4 class="mb-2">Current Address</h4>
                                                        <dl class="row mt-4 mb-0">
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Address:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $current_address->details ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">City:
                                                            </dt>
                                                            <dd class="col-sm-8">
                                                                {{ $current_address->city ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Area:
                                                            </dt>
                                                            <dd class="col-sm-8">
                                                                {{ $current_address->area ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                State:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $current_address->state ?? '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Zip
                                                                Code:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $current_address->zip_code ?? '' }}
                                                            </dd>
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Country:</dt>
                                                            <dd class="col-sm-8">
                                                                <span class="text-capitalize">{{ $current_address->country ?? '' }}</span>
                                                            </dd>
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Mobile Number:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ $current_address->phone_number ?? '' }}
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="d-flex flex-column text-start text-lg-end">
                                                        <div class="d-flex order-sm-0 order-1">
                                                            <button class="btn btn-label-primary me-3 waves-effect edit-btn" data-toggle="tooltip" data-placement="top" title="Edit Current Address" data-edit-url="{{ route('user_contacts.edit', $user_current_address->id) }}" data-url="{{ route('user_contacts.update', $user_current_address->id) }}" type="button" data-bs-toggle="modal" data-bs-target="#addNewAddress">
                                                                Edit
                                                            </button>
                                                            <button class="btn btn-label-secondary waves-effect delete-address" data-toggle="tooltip" data-placement="top" title="Delete" data-type="current-address" data-slug="{{ $user_current_address->id }}" data-del-url="{{ route('user_contacts.destroy', $user_current_address->id) }}">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="col-12">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <h4 class="mb-2">Current Address</h4>
                                                    </div>
                                                    <div class="d-flex flex-column text-start text-lg-end">
                                                        <div class="d-flex order-sm-0 order-1">
                                                            <button title="Add Current Address" data-type="current_address" data-url="{{ route('user_contacts.store') }}" class="btn btn-primary btn-sm add-btn waves-effect waves-light custom-btn" type="button" data-bs-toggle="modal" data-bs-target="#addNewAddress">
                                                                Add Address
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-emergency-contact" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 order-1 order-xl-0">
                                    <h4>Emergency Contact</h4>
                                    <hr />
                                    <div class="content py-primary">
                                        @if (isset($user_emergency_contacts) && !empty($user_emergency_contacts))
                                        @foreach ($user_emergency_contacts as $user_emergency_contact)
                                        @php $contact_details = json_decode($user_emergency_contact->value); @endphp
                                        <div class="col-12" id="id-{{ $user_emergency_contact->id }}">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <dl class="row mb-0">
                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Name:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ isset($contact_details->name) ? $contact_details->name : '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Relation:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ isset($contact_details->relationship) ? $contact_details->relationship : '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Mobile Number:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ isset($contact_details->phone_number) ? $contact_details->phone_number : '' }}
                                                            </dd>

                                                            <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">
                                                                Address:</dt>
                                                            <dd class="col-sm-8">
                                                                {{ isset($contact_details->address_details) ? $contact_details->address_details : '' }}
                                                            </dd>
                                                        </dl>
                                                    </div>
                                                    <div class="d-flex flex-column text-start text-lg-end">
                                                        <div class="d-flex order-sm-0 order-1">
                                                            <button data-toggle="tooltip" data-placement="top" title="Edit Emergency Contact" type="button" data-bs-toggle="modal" data-bs-target="#addEmergencyContact" data-edit-url="{{ route('user_contacts.edit', $user_emergency_contact->id) }}" data-url="{{ route('user_contacts.update', $user_emergency_contact->id) }}" class="btn edit-btn btn-primary btn-sm add-btn waves-effect waves-light me-2">
                                                                Edit
                                                            </button>

                                                            <button data-toggle="tooltip" data-placement="top" title="Delete" data-slug="{{ $user_emergency_contact->id }}" class="btn delete-address btn-label-secondary waves-effect" data-type="emergency_contact" data-del-url="{{ route('user_contacts.destroy', $user_emergency_contact->id) }}">
                                                                Delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @endif
                                        <div class="col-12">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <h6 class="mb-2 pt-1">Emergency Contact</h6>
                                                        <p class="mb-0">You can add multiple contacts</p>
                                                    </div>
                                                    <div class="d-flex flex-column text-start text-lg-end">
                                                        <div class="d-flex order-sm-0 order-1">
                                                            <button title="Add Emergency Contact" data-type="emergency_contact" data-url="{{ route('user_contacts.store') }}" class="btn btn-primary btn-sm add-btn waves-effect waves-light custom-btn" type="button" data-bs-toggle="modal" data-bs-target="#addEmergencyContact">
                                                                Add Contact
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-notification" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 order-1 order-xl-0">
                                    <h4>All Notifications</h4>
                                    <hr />
                                    <div class="content py-primary">
                                        <div class="col-12">
                                            <div class="cardMaster border p-3 rounded mb-3">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                    <div class="card-information">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">User</th>
                                                                    <th scope="col">Title</th>
                                                                    <th scope="col">Timing</th>
                                                                    <th scope="col">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="body">
                                                                @php $counter = 1; @endphp
                                                                @foreach (auth()->user()->notifications as $notification)
                                                                <tr class="odd" id="id-{{ $notification->data['id'] }}">
                                                                    <td class="sorting_1">
                                                                        <div class="d-flex justify-content-start align-items-center user-name">
                                                                            <div class="avatar-wrapper">
                                                                                <div class="avatar avatar-sm me-3">
                                                                                    @if (isset($notification->data['profile']) && !empty($notification->data['profile']))
                                                                                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$notification->data['profile'], null) }}" alt="Avatar" class="rounded-circle">
                                                                                    @else
                                                                                    <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex flex-column">
                                                                                <a href="#" class="text-body text-truncate">
                                                                                    <span class="fw-semibold">{{ Str::ucfirst($notification->data['name']) }}</span>
                                                                                </a>
                                                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <span class="text-truncate d-flex align-items-center">
                                                                            {!! $notification->data['title'] !!}
                                                                        </span>
                                                                    </td>
                                                                    <td>{{ date('d M Y h:i A', strtotime($notification->created_at)) }}
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-primary btn-sm waves-effect waves-light me-2 show" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#notification-details-modal" data-toggle="tooltip" data-placement="top" title="Show Notification Details" data-show-url="{{ route('notifications.show', $notification->id) }}">
                                                                            <i class="fa fa-eye"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-job-history" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <!-- Timeline Advanced-->
                                <div class="col-xl-12">
                                    <h4>Job History</h4>
                                    <hr />
                                    <div class="content py-primary">
                                        <div class="card-body pb-0">
                                            <ul class="timeline mt-5 mb-0">
                                                @foreach ($job_histories as $job_history)
                                                <li class="timeline-item timeline-item-secondary pb-4 border-left-dashed">
                                                    <span class="timeline-indicator timeline-indicator-primary">
                                                        <i class="ti ti-user-circle"></i>
                                                    </span>
                                                    <div class="timeline-event">
                                                        <div class="timeline-header border-bottom mb-3">
                                                            <h6 class="mb-0">
                                                                @if (isset($job_history->designation) && !empty($job_history->designation->title))
                                                                {{ $job_history->designation->title }}
                                                                @else
                                                                -
                                                                @endif
                                                            </h6>
                                                            <span class="text-muted">
                                                                @if (!empty($job_history->joining_date))
                                                                {{ date('d M Y', strtotime($job_history->joining_date)) }}
                                                                @else
                                                                -
                                                                @endif

                                                                @if (isset($job_history->end_date) && !empty($job_history->end_date))
                                                                -
                                                                {{ date('d M Y', strtotime($job_history->end_date)) }}
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <div class="d-flex justify-content-between flex-wrap mb-2">
                                                            <div>
                                                                <span>
                                                                    @if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Department Manager'))
                                                                    @if (isset($job_history->user->hasManagerDepartment) && !empty($job_history->user->hasManagerDepartment))
                                                                    {{ $job_history->user->hasManagerDepartment->name }}
                                                                    @endif
                                                                    @else
                                                                    @if (isset($job_history->user->departmentBridge->department) && !empty($job_history->user->departmentBridge->department))
                                                                    {{ $job_history->user->departmentBridge->department->name }}
                                                                    @endif
                                                                    @endif
                                                                </span>
                                                                <i class="ti ti-arrow-right scaleX-n1-rtl mx-3"></i>
                                                                <span>
                                                                    PKR.
                                                                    @if(!empty($job_history->salary->salary))
                                                                    {{ number_format($job_history->salary->salary) }}
                                                                    @else
                                                                    0.00
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div>
                                                                @if (!empty($job_history->salary->effective_date))
                                                                {{ date('d M Y', strtotime($job_history->salary->effective_date)) }}
                                                                @endif

                                                                @if (isset($job_history->salary->end_date) && !empty($job_history->salary->end_date))
                                                                -
                                                                {{ date('d M Y', strtotime($job_history->salary->end_date)) }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Timeline Advanced-->
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade  " id="user-documents" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" style="text-align: right">
                                    <button data-toggle="tooltip" data-placement="top" title="Add Attachments" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-document-btn" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#documentUploadModal">
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pl-md-2 pt-md-0 pt-sm-4 pt-4">
                                    {{-- documents list --}}
                                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="container">
                                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Attachments</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- documents list --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade  " id="user-assets" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 pl-md-2 pt-md-0 pt-sm-4 pt-4">
                                    {{-- assets assigned to this user  --}}
                                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                                        <div class="container">
                                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border assets_data_table table-responsive table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">UID</th>
                                                        <th scope="col">Category</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Assigned Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    {{-- assets assigned to this user  --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Address Modal -->
    <div class="modal fade" id="addNewAddress" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2" id="modal-label">Add New Address</h3>
                    </div>
                    <form id="create-form" class="row g-3" data-modal-id="addNewAddress" data-method="" action="">
                        @csrf

                        <span id="edit-content">
                            <input type="hidden" name="type" id="form-type" />
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="details">Address Details <span class="text-danger">*</span></label>
                                <textarea name="details" id="details" class="form-control" rows="3" placeholder="Enter address details"></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="details_error" class="text-danger error"></span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="area">Area <span class="text-danger">*</span></label>
                                    <input type="text" id="area" name="area" class="form-control" placeholder="Enter area name" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="area_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="Enter city name" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="city_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="state">State <span class="text-danger">*</span></label>
                                    <input type="text" id="state" name="state" class="form-control" placeholder="Enter state name" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="state_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="zip_code">Zip code</label>
                                    <input type="text" id="zip_code" name="zip_code" class="form-control" placeholder="Enter zip code" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="zip_code_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="country">Country <span class="text-danger">*</span></label>
                                    <select id="country" name="country" class="select2 form-select" data-allow-clear="true">
                                        <option value="" selected>Select Country</option>
                                        <option value="pakistan">Pakistan</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="country_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="phone_number">Mobile Number </label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control mobileNumber" placeholder="Enter phone number" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="phone_number_error" class="text-danger error"></span>
                                </div>
                            </div>
                        </span>

                        <div class="col-12 mt-3 action-btn">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 applyDiscrepancyLeaveBtn">Submit</button>
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
    <!--/ Add New Address Modal -->

    <!-- Add Emergency Contact Modal -->
    <div class="modal fade" id="addEmergencyContact" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-simple modal-add-new-address">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="address-title mb-2" id="modal-label"></h3>
                    </div>
                    <form id="create-form" class="row g-3" data-modal-id="addEmergencyContact" data-method="" action="">
                        @csrf

                        <span id="edit-content">
                            <input type="hidden" name="type" id="form-type" />
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter name" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="name_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="relationship">Relationship <span class="text-danger">*</span></label>
                                    <input type="text" id="relationship" name="relationship" class="form-control" placeholder="Enter relationship" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="relationship_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="phone_number">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control mobileNumber" placeholder="Enter phone number" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="phone_number_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="email_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 mt-2">
                                <label class="form-label" for="address_details">Address Details <span class="text-danger">*</span></label>
                                <textarea name="address_details" id="" rows="4" class="form-control" placeholder="Enter address details"></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="address_details_error" class="text-danger error"></span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="country">Country <span class="text-danger">*</span></label>
                                    <select id="country" name="country" class="form-control">
                                        <option value="">Select Country</option>
                                        <option value="pakistan" selected>Pakistan</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="country_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label" for="city">City <span class="text-danger">*</span></label>
                                    <input type="text" id="city" name="city" class="form-control" placeholder="Enter city name" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="city_error" class="text-danger error"></span>
                                </div>
                            </div>
                        </span>

                        <div class="col-12 mt-3 action-btn">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 applyDiscrepancyLeaveBtn">Submit</button>
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
    <!--/ Add New Address Modal -->
    <!-- View Department Details Modal -->
    <div class="modal fade" id="notification-details-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>

                    <div class="col-12">
                        <span id="show-content"></span>
                    </div>

                    <div class="col-12 mt-3">
                        <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View Department Details Modal -->
</div>


{{-- add documents modal --}}
@include('admin.profile.documents.partials.createModal')
{{-- add documents modal --}}



{{-- view documents modal --}}
@include('admin.profile.documents.partials.viewModal')
{{-- view documents modal --}}



{{-- edit docuiments modal --}}
@include('admin.profile.documents.partials.editModal')
{{-- edit docuiments modal --}}



<input type="hidden" name="" id="file_icon_url" value="{{ asset('public/admin/assets/img/fileicon.png') }}">
<input type="hidden" name="" id="doc_icon_url" value="{{ asset('public/admin/assets/img/doc.png') }}">
<input type="hidden" name="" id="pdf_icon_url" value="{{ asset('public/admin/assets/img/pdf.png') }}">
<input type="hidden" name="" id="xls_icon_url" value="{{ asset('public/admin/assets/img/xls.png') }}">

@endsection
@push('js')
<script>
    $('.delete-address').on('click', function() {
        var slug = $(this).attr('data-slug');
        var delete_url = $(this).attr('data-del-url');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: delete_url,
                    type: 'DELETE',
                    success: function(response) {
                        if (response) {
                            $('#id-' + slug).hide();
                            Swal.fire(
                                'Deleted!',
                                'Your record has been deleted.',
                                'success'
                            )
                            setInterval('location.reload()', 2000)
                        } else {
                            Swal.fire(
                                'Not Deleted!',
                                'Sorry! Something went wrong.',
                                'danger'
                            )
                        }
                    }
                });
            }
        })
    });

    $(document).on("click", ".custom-btn", function() {
        var targeted_modal = $(this).attr("data-bs-target");
        var type = $(this).attr("data-type");

        $(targeted_modal).find("#create-form").find("#form-type").val(type);
    });
    $(document).ready(function() {
        // When the file input changes
        $("#cover_image_id").change(function() {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                // Create an image element
                var img = $('<img style="width:10%; height:5%">').attr("src", e.target.result);

                // Display the image preview
                $("#cover_image_id-preview").html(img);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        });

        $("#profile").change(function() {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                // Create an image element
                var img = $('<img width="85"/>').attr("src", e.target.result);

                // Display the image preview
                $("#profile-preview").html(img);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        });

        $("#cnic_front").change(function() {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                // Create an image element
                var img = $('<img style="width:50%; height:100px">').attr("src", e.target.result);

                // Display the image preview
                $("#cnic_front-preview").html(img);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        });

        $("#cnic_back").change(function() {
            var file = this.files[0];
            var reader = new FileReader();

            reader.onload = function(e) {
                // Create an image element
                var img = $('<img style="width:50%; height:100px">').attr("src", e.target.result);

                // Display the image preview
                $("#cnic_back-preview").html(img);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        });

        // $('#edit-profile input, #edit-profile select, #edit-profile textarea').prop('disabled', true);

        // $('#editButton').click(function() {
        //   $(this).addClass('d-none');
        //   $('#updateButton').removeClass('d-none');
        //   $('#edit-profile input, #edit-profile select, #edit-profile textarea').prop('disabled', false);
        // });

        $("#edit-profile").submit(function(event) {
            event.preventDefault();
            var first_name = $('#first_name').val();
            var gender = $('#gender').val();
            var date_of_birth = $('#date_of_birth').val();
            var cnic = $('#cnic_number').val();
            var mobileNumber = $('#phone_number').val();

            var isValid = true;

            if (mobileNumber.length > 0 && mobileNumber.length < 12) {
                $('#phone_number_error').text('Mobile number length is not correct.');
                isValid = false;
            } else {
                $('#phone_number_error').text('');
            }
            if (cnic.length > 1 && cnic.length < 15) {
                $('#cnic_error').text('CNIC number length is not correct.');
                isValid = false;
            } else {
                $('#cnic_error').text('');
            }
            if (first_name == '') {
                $('#first_name_error').html('First name is required.');
                isValid = false;
            } else {
                $('#first_name_error').html('');
            }
            // if(gender==''){
            //     $('#gender_error').html('Gender is required.');
            //     isValid = false;
            // }else{
            //     $('#gender_error').html('');
            // }
            // if(date_of_birth === ''){
            //     $('#date_of_birth_error').html('Date of birth is required.');
            //     isValid = false;
            // }else{
            //     $('#date_of_birth_error').html('');
            // }
            if (isValid) {
                this.submit(); // Submit the form
            }
        });



    });

    $(document).on('keyup', '#cnic_number', function() {
        // Get the input value
        var cnic = $(this).val();

        if (cnic.length > 0 && cnic.length < 15) {
            // Display an error message if the input doesn't match the pattern
            $('#cnic_error').text('CNIC number length is not correct.');
        } else {
            // Clear the error message if the input is valid
            $('#cnic_error').text('');
        }
    });
</script>
{{-- documents modal script --}}
<script>
    function showSuccess(msg) {
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success(msg);
    }
    // error function
    function showError(msg) {
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error(msg);
    }

    //list
    $(document).ready(function() {
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('profile.getUserDocuments') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'attachment_count',
                    name: 'attachment_count'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });


        // add more rows button
        $(document).on('click', '.add-more-btn', function() {
            var val = parseInt($(this).attr('data-val'));
            var html = '';
            html = '<span class="document_data">' +
                '<div class="row mt-4 w-full  position-relative">' +
                '<div class="close-btn-wrapper position-absolute d-flex flex-row-reverse mb-3" style="top: -20px !important;">' +
                '<button type="button" class="btn btn-label-primary btn-sm btn-data-close p-2"><i class="fa fa-close icon-close"></i></button>' +
                '</div>' +
                '<div class="col-md-12 mt-3">' +
                '<input type="text" id="title" name="titles[]" class="form-control titles titles_value_' +
                val +
                '"  placeholder="Enter Title" data-count="' +
                val + '" />' +
                '<div class="fv-plugins-message-container invalid-feedback"></div>' +
                '<span id="titles_error_' + val + '" class="text-danger error"></span>' +
                '</div>' +
                '<div class="col-md-12 mt-3">' +
                '<input type="file" id="attachments" name="attachments[]" data-val="' + val +
                '" class="form-control input-file attachments attachments_' + val + '" accept="image/*,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document , .pdf, .txt" + />' +
                '<div class="fv-plugins-message-container invalid-feedback"></div>' +
                '<span id="attachments_error_' + val + '" class="text-danger error"></span>' +
                '</div>' +
                '<div class="col-md-3 mt-3">' +
                '<div class="preview-container-' + val + '"></div>' +
                '</div>' +
                '</div>' +
                '</span>';
            $(this).attr('data-val', val + 1);

            $('#add-more-data').append(html);
        });

        // modal close button
        $(document).on('click', '.btn-data-close', function() {
            $(this).parents('.document_data').remove();
        });

        // on file change
        $(document).on('change', ".input-file", function() {
            var val = $(this).attr('data-val');
            var file = this.files[0];
            var fileIcon = $("#file_icon_url").val();
            var docfileIcon = $("#doc_icon_url").val();
            var pdffileIcon = $("#pdf_icon_url").val();
            var xlsfileIcon = $("#xls_icon_url").val();


            if (file) {
                var type = file.type;
                if (type) {
                    var reader = new FileReader();
                    var inputElement = this; // Capture the 'this' reference
                    reader.onload = function(e) {
                        // Create an image element
                        if (type == "image/jpeg" || type == "image/png" || type == "image/gif" || type == "image/bmp") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", e.target.result);
                        } else if (type == "application/pdf") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", pdffileIcon);
                        } else if (type == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", docfileIcon);
                        } else if (type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || type == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", xlsfileIcon);
                        } else {
                            var img = $('<img style="width:100%; max-width:60px;">').attr("src", fileIcon);
                        }
                        // Display the image preview
                        $(inputElement).parents('.document_data').find(".preview-container-" + val)
                            .html(img);
                    };
                    // Read the image file as a data URL
                    reader.readAsDataURL(file);

                }
            } else {
                $(".preview-container-" + val).html("")
            }
        });

        // add documents modal button
        $(document).on("click", "#add-document-btn", function() {
            $("#documentUploadModal").modal("show");
        });

        // upload documents function
        $(document).on("submit", "#uploadDocumentOfUserForm", function(e) {
            var errors = [];
            var attachments = [];
            var titles = [];
            var countArray = [];
            $(".titles").each(function() {
                var count = $(this).attr('data-count');
                titles.push($(this).val());
                attachments.push($(".attachments_" + count).val());
                countArray.push(count)
            });

            $.each(countArray, function(key, value) {
                if ($(".spinner-border").hasClass("d-none")) {
                    $(".spinner-border").removeClass("d-none")
                }
                $(".btn_submit").attr("disabled", true)
                var title = $(".titles_value_" + value).val();
                var attachment = $(".attachments_" + value).val();
                if (!title) {
                    $("#titles_error_" + value).html("Required");
                    errors.push(1);
                } else {
                    $("#titles_error_" + value).html("");
                }
                if (!attachment) {
                    $("#attachments_error_" + value).html("Required");
                    errors.push(1);
                } else {
                    $("#attachments_error_" + value).html("");
                }
            });

            if (errors.length > 0) {
                showError("Please fill all required fields")
                if (!$(".spinner-border").hasClass("d-none")) {
                    $(".spinner-border").addClass("d-none")
                }
                $(".btn_submit").attr("disabled", false)
                e.preventDefault();
            } else {

            }
        });

        // edit document button
        $(document).on("click", ".edit-doc-btn", function() {

            var id = $(this).attr("data-id");
            var route = $(this).attr("data-edit-route");
            $.ajax({
                url: route,
                method: "GET",
                data: {
                    id: id,
                },
                beforeSend: function() {
                    $("#editDocumentModalBody").html("")
                },
                success: function(res) {
                    $("#editDocumentModal").modal("show");

                    $("#editDocumentModalBody").html(res)
                },
                error: function(xhr, status, error) {
                    console.log(res);
                }
            });
        });

        $(document).on("submit", "#updateDocumentsForm", function(e) {
            if ($(".spinner-border").hasClass("d-none")) {
                $(".spinner-border").removeClass("d-none")
            }
            $(".btn_submit").attr("disabled", true)
            var editErrors = [];
            var attachments = [];
            var titles = [];
            var countArray = [];
            $(".edit_titles").each(function() {
                var count = $(this).attr('data-count');
                titles.push($(this).val());
                countArray.push(count)
            });

            $.each(countArray, function(key, value) {
                var title = $(".edit_titles_value_" + value).val();
                if (!title) {
                    $("#edit_titles_error_" + value).html("Required");
                    editErrors.push(1);
                } else {
                    $("#edit_titles_error_" + value).html("");
                }
            });
            if (editErrors.length > 0) {
                showError("Please fill all required fields")
                if (!$(".spinner-border").hasClass("d-none")) {
                    $(".spinner-border").addClass("d-none")
                }
                $(".btn_submit").attr("disabled", false)
                e.preventDefault();
            }
        });

        $(document).on("click", ".delete-document-btn", function() {
            var id = $(this).attr("data-id");
            var route = $(this).attr("data-route");
            $.ajax({
                url: route,
                method: "GET",
                data: {
                    id: id,
                },
                beforeSend: function() {},
                success: function(res) {
                    console.log(res)
                    if (res.success == true) {
                        $(".document_row_" + id).remove()
                        table.draw()
                        showSuccess(res.message)
                    }
                },
                error: function(xhr, status, error) {
                    console.log(res);
                }
            });
        });

        $(document).on("click", ".delete-document-with-attachment", function() {
            var id = $(this).attr("data-id");
            var route = $(this).attr("data-route");


            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert user!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                customClass: {
                    confirmButton: 'btn btn-primary me-2',
                    cancelButton: 'btn btn-label-secondary'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: route,
                        method: "GET",
                        data: {
                            id: id,
                        },
                        beforeSend: function() {},
                        success: function(res) {
                            console.log(res)
                            if (res.success == true) {
                                table.draw();
                                showSuccess(res.message)
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(res);
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {

                }
            });





        });

    });
</script>
{{-- documents modal script --}}

<script>
    // assets
    $(document).ready(function() {

        var table = $('.assets_data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('profile.myAssets') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'uid',
                    name: 'uid'
                },
                {
                    data: 'category',
                    name: 'category'
                },
                {
                    data: 'name',
                    name: 'name'
                },

                {
                    data: 'date',
                    name: 'date'
                },
            ]
        });
    });
</script>
@endpush
</div>
