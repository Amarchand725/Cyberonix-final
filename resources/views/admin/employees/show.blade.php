@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">User Profile /</span> Profile</h4>

        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="user-profile-header-banner">
                        @if(isset($model->profile->coverImage) && !empty($model->profile->coverImage))
                        <img src="{{ asset('public/admin/assets/img/pages') }}/{{ $model->profile->coverImage->image }}" style="width:100%" alt="Banner image" class="rounded-top img-fluid">
                        @else
                        <img src="{{ asset('public/admin/assets/img/pages/default.png') }}" alt="Banner image" style="width:100%" class="rounded-top img-fluid">
                        @endif
                    </div>
                    <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4 mt-n4">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                            @if(isset($model->profile) && !empty($model->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->profile->profile, null) }}" style="width: 100px !important; height:100px !important" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img object-fit-cover" />
                            @else
                            <img src="{{ asset('public/admin') }}/default.png" style="width: 100px !important; height:100px !important" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" />
                            @endif
                        </div>
                        <div class="flex-grow-1 mt-3 mt-sm-5">
                            <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                                <div class="user-profile-info">
                                    <h4>{{ $model->first_name }} {{ $model->last_name }} <span data-toggle="tooltip" data-placement="top" title="Employment ID">( {{ $model->profile->employment_id??'-' }} )</span></h4>
                                    <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Designation">
                                            <i class="ti ti-color-swatch"></i>
                                            @if($model->employeeStatus->employmentStatus->name=='Terminated')
                                            @if(isset($model->jobHistoryTerminate->designation->title) && !empty($model->jobHistoryTerminate->designation->title))
                                            {{ $model->jobHistoryTerminate->designation->title }}
                                            @else
                                            -
                                            @endif
                                            @else
                                            @if(isset($model->jobHistory->designation->title) && !empty($model->jobHistory->designation->title))
                                            {{ $model->jobHistory->designation->title }}
                                            @else
                                            -
                                            @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Employment Status">
                                            <i class="ti ti-tag"></i>
                                            @if(isset($model->employeeStatus->employmentStatus) && !empty($model->employeeStatus->employmentStatus->name))
                                            <span class="badge bg-label-{{ $model->employeeStatus->employmentStatus->class }}"> {{ $model->employeeStatus->employmentStatus->name }}</span>
                                            @else
                                            -
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Department">
                                            <i class="ti ti-building"></i>
                                            @if($model->employeeStatus->employmentStatus->name=='Terminated')
                                            @if(isset($model->departmentBridgeTerminate->department) && !empty($model->departmentBridgeTerminate->department->name))
                                            {{ $model->departmentBridgeTerminate->department->name }}
                                            @else
                                            -
                                            @endif
                                            @else
                                            @if(isset($model->departmentBridge->department) && !empty($model->departmentBridge->department->name))
                                            {{ $model->departmentBridge->department->name }}
                                            @else
                                            -
                                            @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Work Shift">
                                            <i class="ti ti-clock"></i>
                                            @if($model->employeeStatus->employmentStatus->name=='Terminated')
                                            @if(isset($model->userWorkingShiftTerminate->workShift) && !empty($model->userWorkingShiftTerminate->workShift->name))
                                            {{ $model->userWorkingShiftTerminate->workShift->name }}
                                            @else
                                            -
                                            @endif
                                            @else
                                            @if(isset($model->userWorkingShift->workShift) && !empty($model->userWorkingShift->workShift->name))
                                            {{ $model->userWorkingShift->workShift->name }}
                                            @else
                                            -
                                            @endif
                                            @endif
                                        </li>
                                        <li class="list-inline-item d-flex gap-1" data-toggle="tooltip" data-placement="top" title="Joining Date">
                                            <i class="ti ti-calendar"></i>
                                            @if($model->employeeStatus->employmentStatus->name=='Terminated')
                                            @if(isset($model->jobHistoryTerminate) && !empty($model->jobHistoryTerminate->joining_date))
                                            Joined {{ date('d M Y', strtotime($model->jobHistoryTerminate->joining_date)) }}
                                            @else
                                            -
                                            @endif
                                            @else
                                            @if(isset($model->jobHistory) && !empty($model->jobHistory->joining_date))
                                            Joined {{ date('d M Y', strtotime($model->jobHistory->joining_date)) }}
                                            @else
                                            -
                                            @endif
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                <a href="{{ URL::previous() }}" class="btn btn-primary">
                                    <i class="fas fa-reply me-1"></i>Go Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Header -->

        <!-- Navbar pills -->
        <div class="row">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            <i class="ti-xs ti ti-user-check me-1"></i> Profile
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-bank-details" aria-controls="navs-top-bank-details" aria-selected="true">
                            <i class="fa fa-building-columns me-1"></i> Bank Account
                        </button>
                    </li>
                    @can('employee-job-history')
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-job-history" aria-controls="navs-top-job-history" aria-selected="true">
                                <i class="ti ti-cell"></i>Job History
                            </button>
                        </li>
                    @endcan
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-cnic" aria-controls="navs-top-cnic" aria-selected="true">
                            <i class="fa fa-id-card"></i>&nbsp CNIC
                        </button>
                    </li>
                </ul>
                <div class="tab-content bg-transparent shadow-none p-0">
                    <div class="tab-pane fade show active" id="navs-top-profile" role="tabpanel">
                        <div class="row">
                            <!--Employee Detail-->
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="card-text text-uppercase">Personal Details </small>
                                            @if(Auth::user()->hasRole('Admin'))
                                            <a href="javascript:;" class="edit-btn btn btn-label-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit Employee" data-edit-url="{{ route('employees.edit', $model->slug) }}" data-url="{{ route('employees.update', $model->slug) }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#create-form-modal">
                                                <i class="ti ti-edit ti-xs"></i>
                                            </a>
                                            @endif
                                        </div>
                                        <div class="info-container">
                                            <ul class="list-unstyled">
                                                <li class="py-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-tag"></i>
                                                        Employee ID:
                                                    </span>
                                                    <span>
                                                        @if(isset($model->profile) && !empty($model->profile->employment_id))
                                                        {{ $model->profile->employment_id }}
                                                        @else
                                                        -
                                                        @endif
                                                    </span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        Full Name:</span>
                                                    <span>{{ $model->first_name??'' }} {{ $model->last_name??'' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        CNIC:</span>
                                                    <span>
                                                        @if(!empty($model->profile))
                                                        {{ $model->profile->cnic??'N/A' }}
                                                        @else
                                                        {{ '-' }}
                                                        @endif
                                                    </span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-message"></i>
                                                        Email:</span>
                                                    <span>{{ $model->email??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-phone"></i>
                                                        Phone Number:</span>
                                                    <span>{{ $model->profile->phone_number??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        Gender:</span>
                                                    <span>{{ Str::ucfirst($model->profile->gender)??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-calendar"></i>
                                                        Birthday:</span>
                                                    <span>{{ $model->profile->date_of_birth??'-' }}</span>
                                                </li>
                                                <li class="mb-3 d-flex gap-3">
                                                    <span class="fw-semibold d-flex gap-2">
                                                        <i class="ti ti-user"></i>
                                                        About:
                                                    </span>
                                                    <span class="text-justify">{{ $model->profile->about_me??'-' }}</span>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--Emergency Contacts and Address-->
                            <div class="col-xl-6 col-lg-6 col-md-6">
                                <!--Emergency-->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <small class="card-text text-uppercase">Emergency Contacts</small>
                                        <div class="info-container pt-3">
                                            <div class="content py-primary">
                                                @if(isset($user_emergency_contacts) && !empty($user_emergency_contacts))
                                                @foreach ($user_emergency_contacts as $user_emergency_contact)
                                                @php $contact_details =
                                                json_decode($user_emergency_contact->value); @endphp
                                                <div class="col-12">
                                                    <div class="cardMaster border p-3 rounded mb-3">
                                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                            <div class="card-information">
                                                                <dl class="row mb-0">
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Name:</dt>
                                                                    <dd class="col-sm-8">{{ isset($contact_details->name)?$contact_details->name:'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Relation:</dt>
                                                                    <dd class="col-sm-8">{{ isset($contact_details->relationship)?$contact_details->relationship:'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Phone Number:</dt>
                                                                    <dd class="col-sm-8">{{ isset($contact_details->phone_number)?$contact_details->phone_number:'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Address:</dt>
                                                                    <dd class="col-sm-8">{{ isset($contact_details->address_details)?$contact_details->address_details:'' }}</dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @else
                                                Not Added Yet
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--Address-->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <small class="card-text text-uppercase">Address Information</small>
                                        <div class="info-container pt-3">
                                            <div class="content py-primary">
                                                @if(!isset($user_current_address) && empty($user_current_address) && !isset($user_permanent_address) && empty($user_permanent_address))
                                                Not Added Yet
                                                @else
                                                @if(isset($user_permanent_address) && !empty($user_permanent_address))
                                                @php $permanent_address = json_decode($user_permanent_address['value']); @endphp
                                                @php $permanent_address = json_decode($user_permanent_address['value']); @endphp
                                                <div class="col-12">
                                                    <div class="cardMaster border p-3 rounded mb-3">
                                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                            <div class="card-information">
                                                                <h4 class="mb-2">Permanent Address</h4>
                                                                <dl class="row mt-4 mb-0">
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Address:</dt>
                                                                    <dd class="col-sm-8">{{ $permanent_address->details??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">City:</dt>
                                                                    <dd class="col-sm-8">{{ $permanent_address->city??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Area:</dt>
                                                                    <dd class="col-sm-8">{{ $permanent_address->area??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">State:</dt>
                                                                    <dd class="col-sm-8">{{ $permanent_address->state??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Zip Code:</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $permanent_address->zip_code??'' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Country:</dt>
                                                                    <dd class="col-sm-8">
                                                                        <span class="text-capitalize">{{ $permanent_address->country??'' }}</span>
                                                                    </dd>
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Phone Number:</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $permanent_address->phone_number??'' }}
                                                                    </dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if(isset($user_current_address) && !empty($user_current_address))
                                                @php $current_address = json_decode($user_current_address['value']); @endphp
                                                <div class="col-12">
                                                    <div class="cardMaster border p-3 rounded mb-3">
                                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                                            <div class="card-information">
                                                                <h4 class="mb-2">Current Address</h4>
                                                                <dl class="row mt-4 mb-0">
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Address:</dt>
                                                                    <dd class="col-sm-8">{{ $current_address->details??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">City:</dt>
                                                                    <dd class="col-sm-8">{{ $current_address->city??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Area:</dt>
                                                                    <dd class="col-sm-8">{{ $current_address->area??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">State:</dt>
                                                                    <dd class="col-sm-8">{{ $current_address->state??'' }}</dd>

                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Zip Code:</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $current_address->zip_code??'' }}
                                                                    </dd>
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Country:</dt>
                                                                    <dd class="col-sm-8">
                                                                        <span class="text-capitalize">{{ $current_address->country??'' }}</span>
                                                                    </dd>
                                                                    <dt class="col-sm-4 mb-2 fw-semibold text-nowrap">Phone Number:</dt>
                                                                    <dd class="col-sm-8">
                                                                        {{ $current_address->phone_number??'' }}
                                                                    </dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-bank-details" role="tabpanel">
                        <div class="row">
                            <section id="profile-info">
                                <!-- Bank Details Cards -->
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <small class="card-text text-uppercase">Bank Account Details</small>
                                                    <div class="info-container">
                                                        <ul class="list-unstyled">
                                                            <li class="my-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-home"></i>
                                                                    Bank :
                                                                </span>
                                                                <span>
                                                                    @if(isset($model->hasBankDetails) && !empty($model->hasBankDetails->bank_name))
                                                                    {{ $model->hasBankDetails->bank_name }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-tag"></i>
                                                                    Branch Code :</span>
                                                                <span>
                                                                    @if(isset($model->hasBankDetails) && !empty($model->hasBankDetails->branch_code))
                                                                    {{ $model->hasBankDetails->branch_code }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-user"></i>
                                                                    Title :</span>
                                                                <span>
                                                                    @if(isset($model->hasBankDetails) && !empty($model->hasBankDetails->title))
                                                                    {{ $model->hasBankDetails->title }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-shield"></i>
                                                                    Account Number :</span>
                                                                <span>
                                                                    @if(isset($model->hasBankDetails) && !empty($model->hasBankDetails->account))
                                                                    {{ $model->hasBankDetails->account }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </span>
                                                            </li>
                                                            <li class="mb-3 d-flex gap-3">
                                                                <span class="fw-semibold d-flex gap-2">
                                                                    <i class="ti ti-shield"></i>
                                                                    IBAN Number :</span>
                                                                <span>
                                                                    @if(isset($model->hasBankDetails) && !empty($model->hasBankDetails->iban))
                                                                    {{ $model->hasBankDetails->iban }}
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
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-job-history" role="tabpanel">
                        <div class="row">
                            <section id="profile-info">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="card card-action mb-4">
                                            <div class="card-header align-items-center">
                                                <small class="card-text text-uppercase">Job History Timeline</small>
                                            </div>
                                            <div class="card-body pb-0">
                                                <ul class="timeline mb-0 px-2">
                                                    @foreach ($histories as $history)
                                                    <li class="timeline-item timeline-item-transparent">
                                                        <span class="timeline-point timeline-point-primary"></span>
                                                        <div class="timeline-event">
                                                            <div class="timeline-header">
                                                                <h6 class="mb-0">
                                                                    @if(isset($history->jobHistory->designation) && !empty($history->jobHistory->designation->title))
                                                                    {{ $history->jobHistory->designation->title }}
                                                                    @else
                                                                    -
                                                                    @endif
                                                                </h6>
                                                                <small class="text-muted">
                                                                    {{ date('d M Y', strtotime($history->effective_date)) }}

                                                                    @if(isset($history->jobHistory->end_date) && !empty($history->jobHistory->end_date))
                                                                    - {{ date('d M Y', strtotime($history->jobHistory->end_date)) }}
                                                                    @endif
                                                                </small>
                                                            </div>
                                                            <div class="d-flex flex-wrap">
                                                                <div class="ms-1">
                                                                    <h6 class="mb-0">{{$history->getCurrency->symbol ?? ""}} {{ number_format($history->salary, 2) }}</h6>
                                                                    <span>
                                                                        {{ date('d M Y', strtotime($history->effective_date)) }}

                                                                        @if(isset($history->end_date) && !empty($history->end_date))
                                                                        - {{ date('d M Y', strtotime($history->end_date)) }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navs-top-cnic" role="tabpanel">
                        <div class="row">
                            <section id="profile-info">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <small class="card-text text-uppercase">CNIC FRONT & BACK SIDE</small>
                                                    <div class="info-container">
                                                        <ul class="list-unstyled">
                                                            <li class="my-3 d-flex gap-4">
                                                                @if(isset($model->profile) && !empty($model->profile->cnic_front))
                                                                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->profile->cnic_front }}" alt="CNIC Front" class="rounded img-fluid w-20 img-cnic">
                                                                @else
                                                                <img src="{{ asset('public/admin/assets/img/cnic/front.jpg') }}" alt="No Image" class="rounded img-fluid w-20">
                                                                @endif

                                                                @if(isset($model->profile) && !empty($model->profile->cnic_back))
                                                                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->profile->cnic_back }}" alt="CNIC Back" class="rounded img-fluid w-20 img-cnic">
                                                                @else
                                                                <img src="{{ asset('public/admin/assets/img/cnic/back.jpg') }}" alt="No Image" class="rounded img-fluid w-20">
                                                                @endif
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employee Modal from details page -->
<div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2" id="modal-label"></h3>
                </div>
                <!-- Add role form -->
                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="create-form-modal" id="create-form">
                    @csrf

                    <span id="edit-content">
                        <div class="row">
                            <div class="mb-3 fv-plugins-icon-container col-6">
                                <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" placeholder="Enter first name" name="first_name">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="first_name_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 fv-plugins-icon-container col-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" placeholder="Enter last name" name="last_name">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="last_name_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 fv-plugins-icon-container col-6">
                                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" class="form-control" placeholder="{{config('project.placeholder_email')}}" aria-label="{{config('project.placeholder_email')}}" name="email">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="email_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 fv-plugins-icon-container col-6">
                                <label class="form-label" for="phone_number">Mobile</label>
                                <input type="text" id="phone_number" class="form-control mobileNumber" placeholder="Enter phone number" name="phone_number">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="phone_number_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="d-block form-label">Gender</label>
                            <div class="form-check mb-2">
                                <input type="radio" id="gender-male" name="gender" class="form-check-input" checked required value="male" />
                                <label class="form-check-label" for="gender-male">Male</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" id="gender-female" name="gender" class="form-check-input" required value="female" />
                                <label class="form-check-label" for="gender-female">Female</label>
                            </div>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="gender_error" class="text-danger error"></span>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label" for="employment_id">Employee ID</label>
                                <input type="text" id="employment_id" name="employment_id" class="form-control phone-mask" placeholder="Enter employment id">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="employment_id_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label" for="employment_status_id">Employment Status <span class="text-danger">*</span></label>
                                <select id="employment_status_id" name="employment_status_id" class="form-select select2">
                                    <option value="" selected>Select Status</option>
                                    @if(isset($data['employment_statues']))
                                    @foreach ($data['employment_statues'] as $employment_status)
                                    <option value="{{ $employment_status->id }}">{{ $employment_status->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="employment_status_id_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label" for="role_ids">Role <span class="text-danger">*</span></label>
                                <select id="role_ids" name="role_ids[]" multiple class="form-select select2">
                                    @if(isset($data['roles']))
                                    @foreach ($data['roles'] as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="role_ids_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label" for="designation_id">Designation <span class="text-danger">*</span></label>
                                <select id="designation_id" name="designation_id" class="form-select select2">
                                    <option value="" selected>Select designation</option>
                                    @if(isset($data['designations']))
                                    @foreach ($data['designations'] as $designation)
                                    <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="designation_id_error" class="text-danger error"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label" for="department_id">Departments <span class="text-danger">*</span></label>
                                <select id="department_id" name="department_id" class="form-select select2">
                                    <option value="" selected>Select department</option>
                                    @if(isset($data['departments']))
                                    @foreach ($data['departments'] as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="department_id_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label" for="work_shift_id">Work Shift <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select id="work_shift_id" name="work_shift_id" class="form-select select2">
                                        <option value="">Select work shift</option>
                                        @if(isset($data['work_shifts']))
                                        @foreach ($data['work_shifts'] as $work_shift)
                                        <option value="{{ $work_shift->id }}">{{ $work_shift->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                    <span id="work_shift_id_error" class="text-danger error"></span>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="mb-3 col-6">
                                <label class="form-label" for="salary">Salary</label>
                                <input type="number" id="salary" name="salary" class="form-control" placeholder="Enter salary">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="salary_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 col-6">
                                <label class="form-label" for="multicol-birthdate">Joining Date <span class="text-danger">*</span></label>
                                <input type="date" id="multicol-birthdate" name="joining_date" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="joining_date_error" class="text-danger error"></span>
                            </div>
                        </div>
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
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!-- Edit Employee Modal from details page -->
@endsection
