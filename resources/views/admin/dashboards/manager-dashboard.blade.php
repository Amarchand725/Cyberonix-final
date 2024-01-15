@extends('admin.layouts.app')
@section('title', $data['title'] .'-' . appName())
@push('styles')
<link href="{{ asset('public/admin/assets/vendor/css/pages/page-profile.css') }}" rel="stylesheet" />
@endpush
@section('content')
@php
$statistics = getAttandanceCount($data['user']->id, $data['year']."-".((int)$data['month']-1)."-26", $data['year']."-".(int)$data['month']."-25",'all', $data['shift']);
@endphp
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-9">
            <div class="card mb-4">
                <div class="user-profile-header-banner">
                    @if(isset($data['user']->profile->coverImage) && !empty($data['user']->profile->coverImage) && $data['user']->profile->coverImage->status==1)
                    <img src="{{ asset('public/admin/assets/img/pages') }}/{{ $data['user']->profile->coverImage->image }}" alt="Banner image" class="rounded-top img-fluid">
                    @else
                    <img src="{{ asset('public/admin/assets/img/pages/default.png') }}" alt="Banner image" class="rounded-top img-fluid">
                    @endif
                </div>
                <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                    <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                        @if(isset($data['user']->profile) && !empty($data['user']->profile->profile))
                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$data['user']->profile->profile, null) }}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                        @else
                        <img src="{{ asset('public/admin/assets/img/avatars/default.png') }}" alt="user image" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                        @endif
                    </div>
                    <div class="flex-grow-1 mt-3 mt-sm-5">
                        <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                            <div class="user-profile-info">
                                <h4 class="text-truncate">{{ $data['user']->first_name }} {{ $data['user']->last_name }} <span data-toggle="tooltip" data-placement="top" title="Employment ID">( {{ $data['user']->profile->employment_id??'-' }} )</span></h4>
                                <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                    <li class="list-inline-item" data-toggle="tooltip" data-placement="top" title="Position">
                                        <i class="ti ti-color-swatch"></i>
                                        @if(isset($data['user']->jobHistory->designation->title) && !empty($data['user']->jobHistory->designation->title))
                                        {{ $data['user']->jobHistory->designation->title }}
                                        @else
                                        -
                                        @endif
                                    </li>
                                    <li class="list-inline-item d-xl-block d-none" data-toggle="tooltip" data-placement="top" title="Department">
                                        <i class="ti ti-building"></i>
                                        @if(isset($data['user']->departmentBridge->department) && !empty($data['user']->departmentBridge->department->name))
                                        {{ $data['user']->departmentBridge->department->name }}
                                        @else
                                        -
                                        @endif
                                    </li>
                                </ul>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary waves-effect waves-light">
                                <i class="ti ti-user-check me-1"></i>View Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 d-lg-block d-none">
            <div class="card">
                <div class="reporting-authority position-relative text-center">
                    <div class="card-bg"></div>
                    <div class="card-body">
                        <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto user-profile-header">
                            @if(isset($data['department_manager']->profile) && !empty($data['department_manager']->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$data['department_manager']->profile->profile, null) }}" alt="{{ $data['department_manager']->first_name??'No Image' }}" class="d-block mx-auto rounded user-profile-img-mg border-5">
                            @else
                            <img src="{{ asset('public/admin/default.png') }}" alt="No image" class="d-block mx-auto rounded user-profile-img-mg border-5">
                            @endif
                        </div>
                        <h4 class="mt-2 text-truncate">
                            @if(isset($data['department_manager']->profile) && !empty($data['department_manager']->profile))
                            {{ $data['department_manager']->first_name }} {{ $data['department_manager']->last_name }}
                            @else
                            -
                            @endif
                            <h4>
                                <h5 class="btn-primary d-inline-block px-2 py-1 rounded ra-text">Reporting Authority</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-md-4 col-lg-4 col-12">
            <div class="card manager-welcome position-relative">
                <div class="d-flex align-items-end row">
                    <div class="col-12">
                        <div class="card-body">
                            <h5 class="card-title mb-3 text-capitalize h6 text-truncate">Welcome {{ $data['user']->first_name }} {{ $data['user']->last_name }}! 🎉</h5>
                            <p class="mb-3">
                                Welcome to {{appName() ?? ''}}, where we seamlessly sync your team's rhythm, from clocking in to cashing out, with a symphony of employee management, attendance tracking, salary harmony, ticket solutions, and chat at your fingertips! 🚀🌟🤝
                            </p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary ">View Profile</a>
                            @impersonating($guard = null)
                            <a href="{{ route('impersonate.leave') }}" class="btn btn-primary">Logout</a>
                            @endImpersonating
                        </div>
                    </div>
                    <div class="col-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4 welcome-image">
                            <img src="{{ asset('public/admin/assets/img/illustrations/pencil-rocket.png') }}" height="140" class="position-absolute" alt="Pencil Rocket">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            @php
            $late_in_summary = [];
            $half_day_summary = [];
            $absent_summary = [];

            $graphVal = getGraphValues($data['user']);
            $late_in_summary = $graphVal['late_in_summary'];
            $half_day_summary = $graphVal['half_day_summary'];
            $absent_summary = $graphVal['absent_summary'];

            // $joining_date = Auth::user()->profile->joining_date;
            // $summary_year = date('Y');
            // $s_month = 1;
            // if(date('Y', strtotime($joining_date))==$summary_year){
            //     $s_month = date('n', strtotime($joining_date));
            // }

            // $late_in_summary = [];
            // $half_day_summary = [];
            // $absent_summary = [];
            // for($s_month; $s_month<=date('n'); $s_month++){
            //     $summary_statistics = getAttandanceCount($data['user']->id, $summary_year."-".((int)$s_month-1)."-26", $summary_year."-".(int)$s_month."-25",'all', $data['shift']);

            //     $late_in_summary[] = $summary_statistics['lateIn'];
            //     $half_day_summary[] = $summary_statistics['halfDay'];
            //     $absent_summary[] = $summary_statistics['absent'];
            // }
            @endphp
            <div class="row">
                <div class="col-lg-4 col-md-4 col-12 mb-md-4 mt-md-0 mt-4">
                    <a href="javascript:;" data-user-id="{{ $data['user']->id }}" @if($statistics['lateIn']> 0) data-bs-toggle="modal" data-bs-target="#teamlateinModal" class="late-in-box" @endif data-latein="{{ json_encode($statistics['lateInDates']) }}">
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="card-icon">
                                    <span class="badge bg-label-warning rounded-pill p-2">
                                        <i class="ti ti-credit-card ti-sm"></i>
                                    </span>
                                </div>
                                <h5 class="card-title mb-0 mt-2 h6">Late-In Summary</h5>
                                <small class="text-muted">{{$statistics['lateIn']}} Late-in</small>
                            </div>
                            <div id="lateinSummary" data-late-in-summary="{{ json_encode($late_in_summary) }}"></div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-12 mb-md-4 mt-md-0 mt-4">
                    <a href="javascript:;" data-user-id="{{ $data['user']->id }}" @if($statistics['halfDay']> 0) data-bs-toggle="modal" data-bs-target="#teamhalfdayModal" class="half-day-box" @endif data-halfday="{{ json_encode($statistics['halfDayDates']) }}"
                        @if($data['remaining_filable_leaves'] > 1) data-remaining-leaves="true" @else data-remaining-leaves="false" @endif
                        >
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="card-icon">
                                    <span class="badge bg-label-danger rounded-pill p-2">
                                        <i class="ti ti-credit-card ti-sm"></i>
                                    </span>
                                </div>
                                <h5 class="card-title mb-0 mt-2 h6">Half Day Summary</h5>
                                <small class="text-muted">{{$statistics['halfDay']}} Half Day</small>
                            </div>
                            <div id="halfdaySummary" data-half-day-summary="{{ json_encode($half_day_summary) }}"></div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-12 mb-md-4 mt-md-0 mt-4">
                    <a href="javascript:;" data-user-id="{{ $data['user']->id }}" @if($statistics['absent']> 0) data-bs-toggle="modal" data-bs-target="#teamabsentModal" class="absent-dates-box" @endif data-absent="{{ json_encode($statistics['absent_dates']) }}"
                        @if($data['remaining_filable_leaves'] > 1) data-remaining-leaves="true" @else data-remaining-leaves="false" @endif
                        >
                        <div class="card">
                            <div class="card-body pb-0">
                                <div class="card-icon">
                                    <span class="badge bg-label-danger rounded-pill p-2">
                                        <i class="ti ti-credit-card ti-sm"></i>
                                    </span>
                                </div>
                                <h5 class="card-title mb-0 mt-2 h6">Absent Summary</h5>
                                <small class="text-muted">{{$statistics['absent']}} Absent</small>
                            </div>
                            <div id="absentSummary" data-absent-summary="{{ json_encode($absent_summary) }}"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card mb-lg-0 mb-4 mt-md-0 mt-4">
                        <h5 class="card-header">News & Update</h5>
                        <div class="card-body manager-news scroll-right pb-0">
                            <ul class="timeline mb-0">
                                @foreach ($data['announcements'] as $announcement)
                                <li class="timeline-item timeline-item-transparent">
                                    <span class="timeline-point timeline-point-primary"></span>
                                    <div class="timeline-event">
                                        <div class="timeline-header border-bottom mb-3 py-2">
                                            <h6 class="mb-0">{{ $announcement->title }},</h6>
                                            <span class="text-primary">{{ date('d M', strtotime($announcement->created_at)) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between flex-wrap mb-2">
                                            <div>{!! $announcement->description !!}</div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                            <small class="d-block mb-1 text-muted">Today Summary</small>
                                            <div>
                                                <div class="time d-xl-block d-none">
                                                    <span class="hms"></span>
                                                    <span class="ampm"></span>
                                                    <br>
                                                    <span class="date"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="card-title mb-1">
                                            {{ $data['punchedIn_date'] }}
                                            <!--This is for WFH Users checkout button-->
                                            @if(isset($data['user']->hasWFHEmployee) && !empty($data['user']->hasWFHEmployee))
                                                @if($data['punchedIn_time']=='Not yet')
                                                    <a href="{{ route('user.wfh_check_in') }}" class="btn btn-info btn-sm">Check In</a>
                                                @else
                                                    <a href="{{ route('user.wfh_checkout') }}" class="btn btn-primary btn-sm">Check Out</a>
                                                @endif
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="d-flex gap-2 align-items-center mb-2">
                                                    <span class="badge bg-label-info p-1 rounded"><i class="ti ti-arrow-bar-to-down ti-xs"></i></span>
                                                    <p class="mb-0 clock-in">Check In</p>
                                                </div>
                                                <h5 class="mb-0 pt-1 text-nowrap">
                                                    {{ $data['punchedIn_time'] }}
                                                </h5>
                                            </div>
                                            <div class="col-2">
                                                <div class="divider divider-vertical">
                                                </div>
                                            </div>
                                            <div class="col-5  text-end">
                                                <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                                                    <p class="mb-0 clock-out">Check Out</p>
                                                    <span class="badge bg-label-danger p-1 rounded"><i class="ti ti-arrow-bar-up ti-xs"></i></span>
                                                </div>
                                                <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">
                                                    {{ $data['punchedOut_time'] }}
                                                </h5>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-4">
                                            <div class="progress w-100" style="height: 8px">
                                                <div class="progress-bar bg-info" style="width: {{ $data['check_in_to_current_duration_of_shift'] }}%" role="progressbar" aria-valuenow="{{ $data['check_in_to_current_duration_of_shift'] }}" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" title="Time: {{ $data['currentDateTime'] }}">
                                                </div>
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $data['remaining_duration_shift'] }}%" aria-valuenow="{{ $data['remaining_duration_shift'] }}" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" title="End Shift: {{ $data['endShiftTime'] }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex justify-content-between pb-0">
                                        <div class="card-title">
                                            <h5 class="m-0 me-2 text-truncate">Team Discrepancy & Leaves</h5>
                                            <small class="text-muted">This Month</small>
                                        </div>
                                    </div>
                                    <div class="nav-align-top">
                                        <div class="card-body pb-3 pt-1">
                                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                                <li class="nav-item">
                                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-discrepancy" aria-controls="navs-justified-discrepancy" aria-selected="true">Discrepancy</button>
                                                </li>
                                                <li class="nav-item">
                                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-leaves" aria-controls="navs-justified-leaves" aria-selected="false">Leaves</button>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="tab-content p-0 pt-2 pb-2">
                                            <div class="tab-pane fade show active" id="navs-justified-discrepancy" role="tabpanel">
                                                <div class="table-responsive text-nowrap scroll-bottom scroll-right input-checkbox manager-team-discrepancy-scroll">
                                                    <div class="text-end mb-3 pe-3">
                                                        <a href="javascript:;" data-show-url="{{ route('team.attendance.get-discrepancies') }}" data-toggle="tooltip" data-placement="top" title="Team Discrepancies" @if(count($data['current_month_discrepancies'])> 0)
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#teamdiscrepancyModal"
                                                            class="btn btn-primary waves-effect waves-light btn-sm view-modal-btn"
                                                            @else
                                                            class="btn btn-primary waves-effect waves-light btn-sm"
                                                            @endif
                                                            >
                                                            View All
                                                        </a>
                                                        <button data-modal-id="navs-justified-discrepancy" class="btn btn-primary btn-sm approve-btn" data-status-type="status" disabled data-status-url="{{ route('user.discrepancy.status') }}">Approve</button>
                                                    </div>
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>
                                                                    <div>
                                                                        <input class="form-check-input select-all" type="checkbox" value="" id="defaultCheck1" />
                                                                    </div>
                                                                </th>
                                                                <th>Employee</th>
                                                                <th>Date</th>
                                                                <th>Type</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="table-border-bottom-0">
                                                            @php $counter = 0; @endphp
                                                            @foreach ($data['current_month_discrepancies'] as $discrepancy)
                                                            @if($discrepancy->status==0)
                                                            @php $counter++; @endphp
                                                            <tr @if(Auth::user()->hasRole('Department Manager') && $discrepancy->status==0 && $discrepancy->is_additional==1)
                                                                data-toggle="tooltip"
                                                                data-placement="top"
                                                                title="Additional Discrepancy"
                                                                @endif
                                                                >
                                                                <td>
                                                                    <div>
                                                                        <input @if($discrepancy->status==1)
                                                                        disabled checked class="form-check-input"
                                                                        @elseif(Auth::user()->hasRole('Department Manager') && $discrepancy->status==0 && $discrepancy->is_additional==1)
                                                                        disabled class="form-check-input"
                                                                        @else
                                                                        class="form-check-input checkbox"
                                                                        @endif
                                                                        type="checkbox"
                                                                        data-type="{{ $discrepancy->type }}"
                                                                        value="{{ $discrepancy->id }}"
                                                                        />
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex justify-content-start align-items-center user-name">
                                                                        <div class="avatar-wrapper">
                                                                            <div class="avatar me-2">
                                                                                @if(isset($discrepancy->hasEmployee->profile) && !empty($discrepancy->hasEmployee->profile->profile))
                                                                                <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $discrepancy->hasEmployee->profile->profile }}" alt="Avatar" class="rounded-circle">
                                                                                @else
                                                                                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex flex-column">
                                                                            <span class="emp_name fw-semibold text-truncate">
                                                                                @if(isset($discrepancy->hasEmployee) && !empty($discrepancy->hasEmployee->first_name))
                                                                                {{ $discrepancy->hasEmployee->first_name }} {{ $discrepancy->hasEmployee->last_name }}
                                                                                @else
                                                                                -
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    {{ date('d M Y', strtotime($discrepancy->date)) }}
                                                                </td>
                                                                <td>
                                                                    @if(isset($discrepancy->hasAttendance->in_date) && !empty($discrepancy->hasAttendance->in_date))
                                                                    <span data-toggle="tooltip" data-placement="top" title="PUNCH TIME: {{ date('h:i A', strtotime($discrepancy->hasAttendance->in_date)) }}" class="badge bg-label-danger" text-capitalized="">{{ Str::ucfirst($discrepancy->type) }}</span>
                                                                    @else
                                                                    <span data-toggle="tooltip" data-placement="top" class="badge bg-label-danger" text-capitalized="">{{ Str::ucfirst($discrepancy->type) }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="text-end pe-4">
                                                                        <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="Discrepancy Details" type="button" class="btn btn-secondary btn-primary btn-sm mx-3 show view-modal-btn" data-show-url="{{ route('user.discrepancy.show', $discrepancy->id) }}" tabindex="0" aria-controls="DataTables_Table_0" data-bs-toggle="modal" data-bs-target="#discrepancyModal">
                                                                            <i class="ti ti-eye ti-sm"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="navs-justified-leaves" role="tabpanel">
                                                <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                                                    <div class="table-responsive text-nowrap scroll-bottom scroll-right input-checkbox  manager-team-discrepancy-scroll">
                                                        <div class="text-end mb-3 pe-3">
                                                            <a href="javascript:;" data-show-url="{{ route('team.attendance.get-leaves') }}" data-toggle="tooltip" data-placement="top" title="Team Leaves" @if(count($data['current_month_leave_requests'])> 0)
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#teamleavesModal"
                                                                class="btn btn-primary waves-effect waves-light btn-sm view-modal-btn"
                                                                @else
                                                                class="btn btn-primary waves-effect waves-light btn-sm"
                                                                @endif
                                                                >
                                                                View All
                                                            </a>
                                                            <button data-modal-id="navs-justified-leaves" class="btn btn-primary btn-sm approve-btn" data-status-type="status" disabled data-status-url="{{ route('user.discrepancy.status') }}">Approve</button>
                                                        </div>
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <div>
                                                                            <input class="form-check-input select-all" type="checkbox" />
                                                                        </div>
                                                                    </th>
                                                                    <th>Employee</th>
                                                                    <th>Date</th>
                                                                    <th>Behavior</th>
                                                                    <th>Leave duration</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                @php $counter = 0; @endphp
                                                                @foreach ($data['current_month_leave_requests'] as $current_month_leave_request)
                                                                @if($current_month_leave_request->status==0 && $counter <= 5) @php $counter++; @endphp <tr>
                                                                    <td>
                                                                        <div>
                                                                            <input @if($current_month_leave_request->status==1)
                                                                            disabled checked class="form-check-input"
                                                                            @else
                                                                            class="form-check-input checkbox"
                                                                            @endif
                                                                            type="checkbox"
                                                                            data-type="{{ $current_month_leave_request->behavior_type }}"
                                                                            value="{{ $current_month_leave_request->id }}"
                                                                            />
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-start align-items-center user-name">
                                                                            <div class="avatar-wrapper">
                                                                                <div class="avatar me-2">
                                                                                    @if(isset($current_month_leave_request->hasEmployee->profile) && !empty($current_month_leave_request->hasEmployee->profile->profile))
                                                                                    <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $current_month_leave_request->hasEmployee->profile->profile }}" alt="Avatar" class="rounded-circle">
                                                                                    @else
                                                                                    <img src="{{ asset('public/admin') }}/default.png" alt="Avatar" class="rounded-circle">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="d-flex flex-column">
                                                                                <span class="emp_name text-truncate">
                                                                                    @if(isset($current_month_leave_request->hasEmployee) && !empty($current_month_leave_request->hasEmployee))
                                                                                    {{ $current_month_leave_request->hasEmployee->first_name }} {{ $current_month_leave_request->hasEmployee->last_name }}
                                                                                    @else
                                                                                    -
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ date('d M Y', strtotime($current_month_leave_request->start_at)) }} to {{ date('d-m-Y', strtotime($current_month_leave_request->end_at)) }}</td>
                                                                    <td>
                                                                        {{ $current_month_leave_request->behavior_type }}
                                                                    </td>
                                                                    <td><span class="badge bg-label-danger me-1"> {{ $current_month_leave_request->duration }}</span></td>
                                                                    <td>
                                                                        <div class="text-end pe-4">
                                                                            <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="Leave Details" type="button" class="btn btn-secondary btn-primary btn-sm mx-3 show" data-show-url="{{ route('user_leaves.show', $current_month_leave_request->id) }}" data-bs-toggle="modal" data-bs-target="#leavesModal" class="btn btn-secondary btn-xxs waves-effect waves-light">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-zoom-filled" width="14" height="14" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                                                    <path d="M14 3.072a8 8 0 0 1 2.617 11.424l4.944 4.943a1.5 1.5 0 0 1 -2.008 2.225l-.114 -.103l-4.943 -4.944a8 8 0 0 1 -12.49 -6.332l-.006 -.285l.005 -.285a8 8 0 0 1 11.995 -6.643z" stroke-width="0" fill="currentColor"></path>
                                                                                </svg>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                    </tr>
                                                                    @endif
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
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="card-header mt-1">My Team</h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="text-end mt-4 pe-4">
                                                <span id="my-team" data-url="{{ route('employees.get-team-members') }}"></span>
                                                <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="My Team" data-bs-toggle="modal" data-show-url="{{ route('employees.get-team-members', $data['user']->id) }}" data-bs-target="#teamModal" class="btn btn-primary waves-effect waves-light btn-sm view-modal-btn team-member-btn disabled">
                                                    View All
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <div class="manager-team-scroll scroll-bottom scroll-right">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0" id="team-members"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="card manager-team-graph mt-md-0 mt-4">
                                        @php
                                        $total_regular_todays = [];
                                        $total_late_in_todays = [];
                                        $total_half_day_todays = [];
                                        $total_absent_todays = [];
                                        $total_team_todays = '';

                                        foreach($data['team_members'] as $team_member){
                                        $current_date = date("Y-m-d");
                                        if(date("H")>=8){
                                        $next_date = date("Y-m-d", strtotime($current_date.'+1 day'));
                                        }else{
                                        $current_date = date("Y-m-d", strtotime($current_date.'-1 day'));
                                        $next_date = date("Y-m-d", strtotime($current_date.'+1 day'));
                                        }

                                        $user_shift = '';
                                        if(!empty($team_member->userWorkingShift)){
                                        $user_shift = $team_member->userWorkingShift->workShift;
                                        }else{
                                        $user_shift = $data['shift'];
                                        }

                                        $attendance_single_record = getAttandanceSingleRecord($team_member->id, $current_date, $next_date,'all', $user_shift);

                                        $punch_outer = '';
                                        if(isset($attendance_single_record['punchOut']) && !empty($attendance_single_record['punchOut'])){
                                        $punch_outer = $attendance_single_record['punchOut'];
                                        }
                                        $attendance_date = '';
                                        if(!empty($attendance_single_record['attendance_date']->in_date)){
                                        $attendance_date = date('d F Y', strtotime($attendance_single_record['attendance_date']->in_date));
                                        }else if(!empty($attendance_single_record['attendance_date'])){
                                        $attendance_date = date('d F Y', strtotime($attendance_single_record['attendance_date']));
                                        }
                                        if($attendance_single_record['type']=='lateIn' || $attendance_single_record['type']=='earlyout'){
                                        $total_late_in_todays[] = [
                                        'employee' => $attendance_single_record['user']->first_name .' '.$attendance_single_record['user']->last_name,
                                        'punchIn' => $attendance_single_record['punchIn'],
                                        'punchOut' => $punch_outer,
                                        'date' => $attendance_date,
                                        'type' => $attendance_single_record['type'],
                                        ];
                                        }else if($attendance_single_record['type']=='lasthalf' || $attendance_single_record['type']=='firsthalf'){
                                        $total_half_day_todays[] = [
                                        'employee' => $attendance_single_record['user']->first_name .' '.$attendance_single_record['user']->last_name,
                                        'punchIn' => $attendance_single_record['punchIn'],
                                        'punchOut' => $punch_outer,
                                        'date' => $attendance_date,
                                        'type' => $attendance_single_record['type'],
                                        ];
                                        }else if($attendance_single_record['type']=='absent'){
                                        $total_absent_todays[] = [
                                        'employee' => $attendance_single_record['user']->first_name .' '.$attendance_single_record['user']->last_name,
                                        'type' => $attendance_single_record['type'],
                                        'date' => date('d F Y', strtotime($attendance_single_record['attendance_date'])),
                                        ];
                                        }else if($attendance_single_record['type']=='regular'){
                                        $total_regular_todays[] = [
                                        'employee' => $attendance_single_record['user']->first_name .' '.$attendance_single_record['user']->last_name,
                                        'punchIn' => $attendance_single_record['punchIn'],
                                        'punchOut' => $punch_outer,
                                        'date' => $attendance_date,
                                        'type' => $attendance_single_record['type'],
                                        ];
                                        }
                                        }

                                        $total_team_todays = array_merge($total_late_in_todays, $total_half_day_todays, $total_absent_todays, $total_regular_todays);
                                        @endphp
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-0">Team Summary</h5>
                                                <small class="text-muted">Today</small>
                                            </div>
                                            <div class="dropdown d-none d-sm-flex">
                                                <a href="javascript:;" data-today-summary="{{ json_encode($total_team_todays) }}" data-toggle="tooltip" data-placement="top" title="Team Members Summary" @if(count($total_team_todays)> 0)
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#adminteamfilterModal"
                                                    class="btn btn-primary waves-effect waves-light btn-sm admin-team-summary-box"
                                                    @else
                                                    class="btn btn-primary waves-effect waves-light btn-sm"
                                                    @endif
                                                    >
                                                    View All
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div id="teamChart" data-regular="{{ count($total_regular_todays) }}" data-late-in="{{ count($total_late_in_todays) }}" data-absent="{{ count($total_absent_todays) }}" data-half-day="{{ count($total_half_day_todays) }}"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-header d-flex justify-content-between pb-2 mb-1">
                                            <div class="card-title mb-1">
                                                <h5 class="m-0 me-2">Attendance Summary</h5>
                                                <small class="text-muted">This Month</small>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" id="salesByCountryTabs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesByCountryTabs">
                                                    <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="nav-align-top">
                                            <div class="card-body pb-3">
                                                <ul class="nav nav-tabs nav-fill manager-summary-list" role="tablist">
                                                    <li class="nav-item">
                                                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-latein" aria-controls="navs-justified-latein" aria-selected="true">Late-in</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-earlyout" aria-controls="navs-justified-earlyout" aria-selected="false">Early Out</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-halfday" aria-controls="navs-justified-halfday" aria-selected="false">Half Day</button>
                                                    </li>
                                                    <li class="nav-item">
                                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-absent" aria-controls="navs-justified-absent" aria-selected="false">Absent</button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="tab-content p-0 pt-2 pb-2">
                                                <div class="tab-pane fade show navs-justified-latein active input-checkbox" id="navs-justified-latein" role="tabpanel">
                                                    <div class="text-end mb-3 pe-3">
                                                        <button data-modal-id="navs-justified-latein" data-url="{{ route('user_leaves.store') }}" class="btn btn-primary btn-sm apply-btn" disabled type="button">Apply</button>
                                                    </div>
                                                    <div class="table-responsive text-nowrap scroll-bottom manager-attendance-scroll scroll-right">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <div>
                                                                            <input class="form-check-input select-all" type="checkbox" value="" id="select-all" />
                                                                        </div>
                                                                    </th>
                                                                    <th>Date</th>
                                                                    <th>Behavior</th>
                                                                    <th>Satus</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                @foreach ($statistics['lateInDates'] as $late_in_date)
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            <input
                                                                                data-user-id="{{ $data['user']->id }}"
                                                                                @if(isset($late_in_date['status']) && $late_in_date['status']!="" )
                                                                                    disabled checked class="form-check-input late-in-dates"
                                                                                @else
                                                                                    class="form-check-input late-in-dates checkbox"
                                                                                @endif
                                                                                type="checkbox" data-type={{ $late_in_date['type'] }}
                                                                                value="{{ $late_in_date['date'] }}"
                                                                            />
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $late_in_date['date'] }}</td>
                                                                    <td>
                                                                        @if($late_in_date['status']=='')
                                                                        <span class="badge bg-label-danger me-1"> Not Applied</span>
                                                                        @else
                                                                        <span class="badge bg-label-warning me-1"> Applied</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($late_in_date['status']==0)
                                                                        <span class="badge bg-label-warning me-1"> Pending</span>
                                                                        @elseif($late_in_date['status']==2)
                                                                        <span class="badge bg-label-danger me-1"> Rejected</span>
                                                                        @elseif($late_in_date['status']==1)
                                                                        <span class="badge bg-label-success me-1"> Approved</span>
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade navs-justified-earlyout input-checkbox" id="navs-justified-earlyout" role="tabpanel">
                                                    <div class="tab-pane fade show active" id="navs-justified-new" role="tabpanel">
                                                        <div class="text-end mb-3 pe-3">
                                                            <button data-modal-id="navs-justified-earlyout" data-url="{{ route('user_leaves.store') }}" class="btn btn-primary btn-sm apply-btn" disabled type="button">Apply</button>
                                                        </div>
                                                        <div class="table-responsive text-nowrap scroll-bottom manager-attendance-scroll scroll-right">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>
                                                                            <div>
                                                                                <input class="form-check-input select-all" type="checkbox" value="" id="select-all" />
                                                                            </div>
                                                                        </th>
                                                                        <th>Date</th>
                                                                        <th>Behavior</th>
                                                                        <th>Satus</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="table-border-bottom-0">
                                                                    @foreach ($statistics['earlyOutDates'] as $early_out_date)
                                                                    <tr>
                                                                        <td>
                                                                            <div>
                                                                                <input
                                                                                    data-user-id="{{ $data['user']->id }}"
                                                                                    @if(isset($early_out_date['status']) && $early_out_date['status']!="" )
                                                                                        disabled checked class="form-check-input early-out-dates"
                                                                                    @else
                                                                                        class="form-check-input early-out-dates checkbox"
                                                                                    @endif
                                                                                    type="checkbox"
                                                                                    data-type={{ $early_out_date['type'] }}
                                                                                    value="{{ $early_out_date['date'] }}"
                                                                                />
                                                                            </div>
                                                                        </td>
                                                                        <td>{{ $early_out_date['date'] }}</td>
                                                                        <td>
                                                                            @if($early_out_date['status']=='')
                                                                            <span class="badge bg-label-danger me-1"> Not Applied</span>
                                                                            @else
                                                                            <span class="badge bg-label-warning me-1"> Applied</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>
                                                                            @if($early_out_date['status']==0)
                                                                            <span class="badge bg-label-warning me-1"> Pending</span>
                                                                            @elseif($early_out_date['status']==2)
                                                                            <span class="badge bg-label-danger me-1"> Rejected</span>
                                                                            @elseif($early_out_date['status']==1)
                                                                            <span class="badge bg-label-success me-1"> Approved</span>
                                                                            @else
                                                                            -
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade navs-justified-halfday input-checkbox" id="navs-justified-halfday" role="tabpanel">
                                                    @if($data['remaining_filable_leaves'] > 1)
                                                    <div class="text-end mb-3 pe-3">
                                                        <button data-modal-id="navs-justified-halfday" data-url="{{ route('user_leaves.store') }}" class="btn btn-primary btn-sm apply-btn" disabled type="button">Apply</button></h3>
                                                    </div>
                                                    @endif
                                                    <div class="table-responsive text-nowrap scroll-bottom manager-attendance-scroll scroll-right">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <div>
                                                                            <input
                                                                                @if($data['remaining_filable_leaves'] < 1)
                                                                                    disabled class="form-check-input"
                                                                                @else
                                                                                    class="form-check-input select-all" id="select-all"
                                                                                @endif
                                                                                type="checkbox"
                                                                            />
                                                                        </div>
                                                                    </th>
                                                                    <th>Date</th>
                                                                    <th>Behavior</th>
                                                                    <th>Satus</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                @foreach ($statistics['halfDayDates'] as $half_date)
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            <input
                                                                                data-user-id="{{ $data['user']->id }}"
                                                                                @if(isset($half_date['status']) && $half_date['status']!="" && $data['remaining_filable_leaves'] < 1)
                                                                                    disabled checked class="form-check-input half-day-dates"
                                                                                @else
                                                                                    class="form-check-input half-day-dates checkbox"
                                                                                @endif
                                                                                type="checkbox"
                                                                                data-type={{ $half_date['type'] }}
                                                                                value="{{ $half_date['date'] }}"
                                                                            />
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $half_date['date'] }}</td>
                                                                    <td>
                                                                        @if($half_date['status']=='')
                                                                        <span class="badge bg-label-danger me-1"> Not Applied</span>
                                                                        @else
                                                                        <span class="badge bg-label-warning me-1"> Applied</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($half_date['status']==0)
                                                                        <span class="badge bg-label-warning me-1"> Pending</span>
                                                                        @elseif($half_date['status']==2)
                                                                        <span class="badge bg-label-danger me-1"> Rejected</span>
                                                                        @elseif($half_date['status']==1)
                                                                        <span class="badge bg-label-success me-1"> Approved</span>
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade navs-justified-absent input-checkbox" id="navs-justified-absent" role="tabpanel">
                                                    @if($data['remaining_filable_leaves'] > 1)
                                                    <div class="text-end mb-3 pe-3">
                                                        <button data-modal-id="navs-justified-absent" data-url="{{ route('user_leaves.store') }}" class="btn btn-primary btn-sm apply-btn" disabled type="button">Apply</button></h3>
                                                    </div>
                                                    @endif
                                                    <div class="table-responsive text-nowrap scroll-bottom manager-attendance-scroll scroll-right">
                                                        <table class="table table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th>
                                                                        <div>
                                                                            <input @if($data['remaining_filable_leaves'] < 1) disabled class="form-check-input" @else id="select-all" class="form-check-input select-all" @endif type="checkbox" />
                                                                        </div>
                                                                    </th>
                                                                    <th>Date</th>
                                                                    <th>Behavior</th>
                                                                    <th>Satus</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="table-border-bottom-0">
                                                                @foreach ($statistics['absent_dates'] as $absent_date)
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            <input
                                                                                data-user-id="{{ $data['user']->id }}"
                                                                                @if(isset($absent_date['status']) && $absent_date['status']!="" || $data['remaining_filable_leaves'] < 1)
                                                                                    disabled checked class="form-check-input absent-dates"
                                                                                @else
                                                                                    class="form-check-input absent-dates checkbox"
                                                                                @endif
                                                                                type="checkbox" data-type={{ $absent_date['type'] }}
                                                                                value="{{ $absent_date['date'] }}"
                                                                            />
                                                                        </div>
                                                                    </td>
                                                                    <td>{{ $absent_date['date'] }}</td>
                                                                    <td>
                                                                        @if($absent_date['status']=="")
                                                                        <span class="badge bg-label-danger me-1"> Not Applied</span>
                                                                        @else
                                                                        <span class="badge bg-label-warning me-1"> Applied</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @if($absent_date['status']==0)
                                                                        <span class="badge bg-label-warning me-1"> Pending</span>
                                                                        @elseif($absent_date['status']==2)
                                                                        <span class="badge bg-label-danger me-1"> Rejected</span>
                                                                        @elseif($absent_date['status']==1)
                                                                        <span class="badge bg-label-success me-1"> Approved</span>
                                                                        @else
                                                                        -
                                                                        @endif
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
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @include('admin.dashboards.admin-pop-up-modals') --}}
@include('admin.dashboards.pop-up-modals')
@endsection
@push('js')
<script src="{{ asset('public/admin/assets/js/custom-dashboard.js') }}"></script>
<script>
    //Getting team members after dashboard get ready.
    $(document).ready(function() {
        var get_url = $('#my-team').data('url');

        $.ajax({
            url: get_url,
            method: 'GET',
            success: function(response) {
                if (response.length > 0) {
                    $('.team-member-btn').removeClass('disabled');
                }
                $('#team-members').html(response);
            }
        });
    });

    function updateTime() {
        var dateInfo = new Date();

        /* time */
        var hr,
            _min = (dateInfo.getMinutes() < 10) ? "0" + dateInfo.getMinutes() : dateInfo.getMinutes(),
            sec = (dateInfo.getSeconds() < 10) ? "0" + dateInfo.getSeconds() : dateInfo.getSeconds(),
            ampm = (dateInfo.getHours() >= 12) ? "PM" : "AM";

        // replace 0 with 12 at midnight, subtract 12 from hour if 13–23
        if (dateInfo.getHours() == 0) {
            hr = 12;
        } else if (dateInfo.getHours() > 12) {
            hr = dateInfo.getHours() - 12;
        } else {
            hr = dateInfo.getHours();
        }

        var currentTime = hr + ":" + _min + ":" + sec;

        // print time
        document.getElementsByClassName("hms")[0].innerHTML = currentTime;
        document.getElementsByClassName("ampm")[0].innerHTML = ampm;

        /* date */
        var dow = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ],
            month = [
                "January",
                "February",
                "March",
                "April",
                "May",
                "June",
                "July",
                "August",
                "September",
                "October",
                "November",
                "December"
            ],
            day = dateInfo.getDate();

        // store date
        var currentDate = dow[dateInfo.getDay()] + ", " + month[dateInfo.getMonth()] + " " + day;

        document.getElementsByClassName("date")[0].innerHTML = currentDate;
    };

    // print time and date once, then update them every second
    updateTime();
    setInterval(function() {
        updateTime()
    }, 1000);
</script>
@endpush
