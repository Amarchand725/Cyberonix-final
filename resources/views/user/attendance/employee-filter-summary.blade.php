@extends('admin.layouts.app')
@section('title', $title .' -  ' . appName())
@php use App\Http\Controllers\AttendanceController; @endphp

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <!-- Users List Table -->
                <div class="card mb-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header">
                                <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between border-bottom">
                    <div>
                        <span class="card-title mb-0">
                            <div class="d-flex align-items-center">
                                @if(isset($user->profile) && !empty($user->profile->profile))
                                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$user->profile->profile, null) }}" style="width:40px !important; height:40px !important" alt class="h-auto" />
                                @else
                                    <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                                @endif
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="mx-3">
                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 me-1">{{ Str::ucfirst($user->first_name) }} {{ Str::ucfirst($user->last_name) }}</h6>
                                        </div>
                                        <small class="text-muted">
                                            @if(isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                                {{ $user->jobHistory->designation->title }}
                                            @else
                                                -
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
                <div class="card-header d-flex justify-content-between align-items-center row">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Date Range</label>
                            <input type="text" class="form-control w-100" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" />
                        </div>
                        <div class="col-md-2">
                            <label>Departments</label>
                            @if(isset($departments) && !empty($departments))
                                <select class="select2 form-select" id="department_ids" name="departments[]" multiple>
                                    <option value="All" selected>All Departments</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <label>Employees</label>
                            @include('admin.layouts.employee-dropdown', ['employees' => $data['employees'], 'type' => 'filter'])
                        </div>
                        <div class="col-md-2">
                            <label>Behavior</label>
                            <select class="select2 form-select" id="filter_behavior" name="behavior">
                                <option value="all">All</option>
                                <option value="lateIn">Late In</option>
                                <option value="regular">Regular</option>
                                <option value="earlyout">Early Out</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label></label>
                            <button type="button" disabled id="process" class="btn btn-primary d-none w-100" style="display:none">Processing...</button>
                            <button type="button" id="filter-btn" class="btn btn-primary attendance-filter-btn d-block w-100" data-show-url="{{ route('employee.attendance.advance-filter.summary') }}"><i class="fa fa-search me-2"></i> Filter </button>
                        </div>
                    </div>
                </div>
                <div class="card-header border-bottom">
                    <span id="show-filter-attendance-content"></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function(){
            var input_date_range = $('#flatpickr-range').val();
            var filterButton = $('#filter-btn');

            if(input_date_range === ''){
                filterButton.prop('disabled', true);
            } else {
                filterButton.prop('disabled', false);
            }
        });

        $(document).ready(function(){
            var filterButton = $('#filter-btn');
            var flatpickrRange = $('#flatpickr-range');

            // Attach an event listener for the input change event
            flatpickrRange.on('change', function() {
                if (flatpickrRange.val() !== '') {
                    // If a date range is selected, enable the filter button
                    filterButton.prop('disabled', false);
                } else {
                    // If the input is empty, disable the filter button
                    filterButton.prop('disabled', true);
                }
            });
        });

    </script>
@endpush
