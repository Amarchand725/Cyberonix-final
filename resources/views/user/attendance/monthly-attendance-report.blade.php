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

                    <div class="col-md-8">
                        <label>Employees Filter </label>
                        @include('admin.layouts.employee-dropdown', ['employees' => $data['employees'], 'type' => 'filter'])
                    </div>
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <button class="btn btn-primary waves-effect waves-light w-100" data-current-month="{{ date('F') }}" id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button>
                    </div>
                    <input type="hidden" id="getMonth" value="{{ $month }}" />
                    <input type="hidden" id="getYear" value="{{ $year }}" />
                    <div class="col-md-2">
                        <label class="d-block"></label>
                        <button type="button" disabled id="process" class="btn btn-primary d-none w-100" style="display:none">Processing...</button>
                        <button type="button" id="filter-btn" class="btn btn-primary monthly-attendance-filter-report-btn d-block w-100" data-show-url="{{ route('employee.attendance.monthly.report.filter') }}"><i class="fa fa-search me-2"></i> Filter </button>
                    </div>
                </div>
            </div>
            <div class="card-header border-bottom">
                <span id="show-filter-attendance-content">
                    <div class="row">
                        <div class="col-12 ">
                            <table class="table  attendance-table">
                                <thead>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Working Days</th>
                                    <th>Regular</th>
                                    <th>Late In</th>
                                    <th>Early Out</th>
                                    <th>Half Days</th>
                                    <th>Absents</th>
                                </thead>
                                <tbody>
                                    @php $bool = ''; $counter = 0; @endphp
                                    @foreach($data['users'] as $key=>$f_user)
                                        @php
                                            $counter++;

                                            $total_days = 0;
                                            $regulars = 0;
                                            $late_ins = 0;
                                            $early_outs = 0;
                                            $half_days = 0;
                                            $absents = 0;

                                            $bool = true;
                                            $shift = '';
                                            if(!empty($f_user->userWorkingShift)){
                                                $shift = $f_user->userWorkingShift->workShift;
                                            }else{
                                                if(isset($f_user->departmentBridge->department->departmentWorkShift->workShift) && !empty($f_user->departmentBridge->department->departmentWorkShift->workShift->id)){
                                                    $shift = $f_user->departmentBridge->department->departmentWorkShift->workShift;
                                                }
                                            }

                                            $begin = new DateTime($data['from_date']);
                                            $end = new DateTime($data['to_date']);
                                        @endphp
                                        @if(!empty($shift))
                                            @php
                                                $statistics = getAttandanceCount($f_user->id, $data['from_date'], $data['to_date'], $data['behavior'], $shift);

                                                $total_days = $statistics['totalDays'];
                                                $regulars = $statistics['workDays'];
                                                $late_ins = $statistics['lateIn'];
                                                $early_outs = $statistics['earlyOut'];
                                                $half_days = $statistics['halfDay'];
                                                $absents = $statistics['absent'];
                                            @endphp
                                            <tr>
                                                <td>{{++$key}}</td>
                                                <td>{{getUserName($f_user)}}</td>
                                                <td>{{$total_days}}</td>
                                                <td>{{$regulars}}</td>

                                                {{-- Clickable --}}
                                                <td data-user="{{ getUserName($f_user) }}" @if(count($statistics['lateInDates']) > 0) class="attendance-detail" style="cursor:pointer" @endif data-type="lateIn" data-dates="{{ json_encode($statistics['lateInDates']) }}">
                                                    {{$late_ins}}
                                                </td>
                                                <td data-user="{{ getUserName($f_user) }}" @if(count($statistics['earlyOutDates']) > 0) class="attendance-detail" style="cursor:pointer" @endif data-type="earlyOut" data-dates="{{ json_encode($statistics['earlyOutDates']) }}">
                                                    {{$early_outs}}
                                                </td>
                                                <td data-user="{{ getUserName($f_user) }}" @if(count($statistics['halfDayDates']) > 0) class="attendance-detail" style="cursor:pointer" @endif data-type="halfDay" data-dates="{{ json_encode($statistics['halfDayDates']) }}">
                                                    {{$half_days}}
                                                </td>
                                                <td data-user="{{ getUserName($f_user) }}" @if(count($statistics['absent_dates']) > 0) class="attendance-detail" style="cursor:pointer" @endif data-type="absent" data-dates="{{ json_encode($statistics['absent_dates']) }}">
                                                    {{$absents}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="8">
                                            Displying {{$data['users']->firstItem()}} to {{$data['users']->lastItem()}} of {{$data['users']->total()}} records
                                            <div class="d-flex justify-content-center">
                                                {!! $data['users']->links('pagination::bootstrap-4') !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="modal fade attendance-detail-modal" id="attendance-detail-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="mb-2">Details</h3>
                </div>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3" id="user-name">User: Abc</th>
                            </tr>
                            <tr>
                                <th>SNo.</th>
                                <th>Date</th>
                                <th>Satus</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0" id="attendance-content-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $('.attendance-detail').on('click', function(){
        var userName = $(this).attr('data-user');
        var att_type = $(this).attr('data-type');
        var data = $(this).data('dates');
        $user_name = '<b> Employee Name: '+ userName +'</b>'
        $('#user-name').html($user_name);

        var status = '-';
        if (att_type == 'absent') {
            status = '<span class="badge bg-label-danger">Absent</span>';
        } else if (att_type == 'lateIn' || att_type == 'earlyOut') {
            status = '<span class="badge bg-label-warning">'+ att_type +'</span>';
        }else{
            status = '<span class="badge bg-label-primary">Half Day</pan>';
        }

        var html = '';
        var counter = 1;
        $.each(data, function(index, value) {
            html += '<tr>' +
                        '<td>' + counter++ + '</td>' +
                        '<td>' + value.date + '</td>' +
                        '<td>'+status+'</td>' +
                '</tr>';

            $('#attendance-content-body').html(html);
        });

        $('#attendance-detail-modal').modal('show');
    });

    $(function() {
        var currentMonth = $('#Slipbutton').data('current-month');
        $('#Slipbutton').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            endDate: currentMonth,
        }).on('changeMonth', function(e) {
            var selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            var selectedYear = e.date.getFullYear();
            var selectOptionUrl = "{{ URL::to('employee/monthly/attendance/report') }}/" + selectedMonth + "/" + selectedYear;
            window.location.href = selectOptionUrl;
        });
        const url = new URL(window.location.href);
        const pathname = url.pathname;
        const pathParts = pathname.split('/');
        const year = pathParts.pop();
        const month = pathParts.pop();

        $('#Slipbutton').datepicker('setDate', new Date(year, month - 1));
    });

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
</script>
@endpush
