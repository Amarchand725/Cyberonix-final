@extends('admin.layouts.app')
@section('title', $title .' -  ' . appName())
@php use App\Http\Controllers\AttendanceController; @endphp

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-10">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }} of <b>{{ $fullMonthName }}</b></h4>
                    </div>
                </div>
                <div class="col-md-2" style="text-align: right;">
                    <a href="{{route('employee.attendance.monthly.report.export')}}" class="btn btn-primary waves-effect waves-light me-3 mt-3" id="exportRecords">
                        <span>
                            <i class="fa fa-file-excel me-0 me-sm-1 ti-xs"></i>
                            <span class="d-none d-sm-inline-block">Export as Excel</span>
                        </span>
                    </a>
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
                <div class="row align-items-end">

                    <div class="col-md-6">
                        <label>Employees Filter </label>
                        @if(isset($data['employees']) && !empty($data['employees']))
                        <select class="select2 form-select" id="employees_ids" name="employees[]" multiple>
                            @foreach ($data['employees'] as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <!-- <button class="btn btn-primary waves-effect waves-light w-100" data-current-month="{{ date('F') }}" id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button> -->
                        <input type="month" class="form-control" name="" id="month" value="" placeholder="Select Month">
                    </div>
                    <input type="hidden" id="getMonth" value="{{ $month }}" />
                    <input type="hidden" id="getYear" value="{{ $year }}" />
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <button type="button" disabled id="process" class="btn btn-primary d-none w-100" style="display:none">Processing...</button>
                        <button type="button" id="filter-btn" class="btn btn-primary monthly-attendance-filter-report-btn d-block w-100" data-show-url="{{ route('employee.attendance.monthly.report.filter') }}"><i class="fa fa-search me-2"></i> Filter </button>
                    </div>
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <button class="btn btn-primary waves-effect waves-light w-100" id="refreshButton">Refresh </button>
                    </div>
                </div>
            </div>
            <div class="card-header border-bottom">
                <span id="show-filter-attendance-content">
                    <div class="row">
                        <div class="col-12 ">
                            <table class="attendance-table data_table">
                                <thead>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Shift</th>
                                    <th>Working Days</th>
                                    <th>Regular</th>
                                    <th>Late In</th>
                                    <th>Early Out</th>
                                    <th>Half Days</th>
                                    <th>Absents</th>
                                    <th>Actions</th>
                                </thead>
                            </table>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $(document).ready(function() {
        loadPageData()
    });
    $(document).on("click", "#refreshButton", function() {
        console.log("working")
        loadPageData()
    });
    $(document).on("change", "#month", function() {
        loadPageData()
    });
    $(document).on("change", "#employees_ids", function() {
        loadPageData()
    })
    // $(function() {
    //     var currentMonth = $('#Slipbutton').data('current-month');



    //     $('#Slipbutton').datepicker({
    //         format: 'mm/yyyy',
    //         startView: 'year',
    //         minViewMode: 'months',
    //         endDate: currentMonth,
    //     }).on('changeMonth', function(e) {
    //         var selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
    //         var selectedYear = e.date.getFullYear();
    //         loadPageData();
    //         // alert(selectedMonth+' ---- '+selectedYear);
    //         // var selectOptionUrl = "{{ URL::to('employee/monthly/attendance/report') }}/" + selectedMonth + "/" + selectedYear;
    //         // // alert(selectOptionUrl);
    //         // // $('#Slipbutton').datepicker('setDate', new Date(selectedYear, selectedMonth, 1));
    //         // window.location.href = selectOptionUrl;

    //     });
    //     const url = new URL(window.location.href);
    //     const pathname = url.pathname;
    //     const pathParts = pathname.split('/');
    //     const year = pathParts.pop();
    //     const month = pathParts.pop();

    //     $('#Slipbutton').datepicker('setDate', new Date(year, month - 1));
    // });

    function redirectPage(dropdown) {
        var selectedOption = dropdown.value;

        if (selectedOption !== '') {
            window.location.href = selectedOption;
        }
    }

    $(document).ready(function() {
        var input_employees = $('#employees_ids').val();
        var filterButton = $('#filter-btn');

        if (input_employees == '') {
            filterButton.prop('disabled', true);
        } else {
            filterButton.prop('disabled', false);
        }
    });

    // Attach an event listener for the input change event
    $('#employees_ids').on('change', function() {
        var filterButton = $('#filter-btn');

        if ($(this).val() != '') {
            // If a date range is selected, enable the filter button
            filterButton.prop('disabled', false);
        } else {
            // If the input is empty, disable the filter button
            filterButton.prop('disabled', true);
        }
    });

    function loadPageData() {
        var pageUrl = "{{route('employee.attendance.monthly.attendanceList')}}"
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        var employeeIds = [];
        $.each($("#employees_ids").val(), function(key, value) {
            employeeIds.push(value)
        })
        var users = employeeIds;
        // $.fn.dataTable.ext.errMode = 'throw';
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: pageUrl + "?loaddata=yes",
                type: "GET",
                data: function(d) {
                    d.month = $('#month').val();
                    d.users = employeeIds;
                    // d.department = $('.department').val();
                    // d.shift = $('.shift').val();
                    // d.search = $('input[type="search"]').val();
                },
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(error);
                    console.log(code);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user',
                    name: 'user'
                },
                {
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'working_days',
                    name: 'working_days'
                },
                {
                    data: 'regular_days',
                    name: 'regular_days'
                },
                {
                    data: 'late_in',
                    name: 'late_in'
                },
                {
                    data: 'early_out',
                    name: 'early_out'
                },
                {
                    data: 'half_days',
                    name: 'half_days'
                },
                {
                    data: 'absents',
                    name: 'absents'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ]
        });

    }
</script>
@endpush