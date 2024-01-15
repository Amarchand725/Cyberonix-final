@extends('admin.layouts.app')
@section('title', $title. ' - ' . appName())

@section('content')
<input type="hidden" id="page_url" value="{{ route('employees.index') }}" />
<input type="hidden" id="current_user_slug" value="{{ Auth::user()->slug }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <div>
                    @if(isset($data['employees']) && !empty($data['employees']))
                        <h5 class="card-title mb-3">Search Filter</h5>
                    @endif
                    <div class="d-flex justify-content-between align-items-center row pb-2 gap-3 gap-md-0">
                        <div class="col-lg-3 col-md-3 user_plan">
                            {{-- @if(isset($data['employees']) && !empty($data['employees']))
                                <select class="select2 form-select form-select-lg" data-allow-clear="true" id="employee-slug" onchange="redirectPage(this)">
                                    <option value="" selected>Select employee</option>
                                    @foreach ($data['employees'] as $employee)
                                        @if(!empty($employee))
                                            @php $monthYear = $data['month'].'/'.$data['year']; @endphp
                                            @if (!empty($employee->employeeStatus->end_date) && ($monthYear < date('m/Y', strtotime($employee->employeeStatus->end_date))))
                                                @php
                                                    $lastMonth = date('m', strtotime($employee->employeeStatus->end_date));
                                                    $lastYear = date('Y', strtotime($employee->employeeStatus->end_date));
                                                @endphp
                                                <option value="{{ URL::to('employees/salary_details/'.$lastMonth.'/'.$lastYear.'/'.$employee->slug) }}" data-user-slug="{{ $employee->slug }}" {{ $employee->slug==$data['user']->slug?'selected':'' }}>
                                                    {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                                                </option>
                                            @else
                                                <option value="{{ URL::to('employees/salary_details/'.$data['month'].'/'.$data['year'].'/'.$employee->slug) }}" data-user-slug="{{ $employee->slug }}" {{ $employee->slug==$data['user']->slug?'selected':'' }}>
                                                    {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                                                </option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            @endif --}}

                            @php $url = URL::to('employees/salary_details') @endphp
                            @include('admin.layouts.employee-dropdown', ['employees' => $data['employees'], 'user' => $data['user'], 'url' => $url, 'month' => $data['month'], 'year' => $data['year'], 'type' => 'salary-details'])
                        </div>
                        <div class="col-md-3 pt-2 text-end">
                            <button class="btn btn-primary waves-effect waves-light" data-joining-date="{{ $data['user_joining_date'] }}" data-current-month="{{ $data['currentMonth'] }}" id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button>
                        </div>
                    </div>
                </div>
                <div id="printable_div">
                    <div class="col-12 mt-3">
                        <span class="card-title mb-0">
                            <a href="{{ route('employees.show', $data['user']->slug) }}" class="text-body text-truncate">
                                <div class="user-profile-header mt-4 d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                                    <div class="flex-shrink-0 mt-2 mx-sm-0 mx-auto">
                                        @if(isset($data['user']->profile) && !empty($data['user']->profile->profile))
                                            <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $data['user']->profile->profile }}" width="70" height="70" alt="user image" class="d-block rounded-circle object-fit-cover" />
                                        @else
                                            <img src="{{ asset('public/admin') }}/default.png" alt="Default image" width="70" height="70" class="d-block rounded-circle object-fit-cover" />
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-md-center align-items-sm-center align-items-center justify-content-md-between justify-content-start ms-4 flex-md-row flex-column gap-4">
                                            <div class="user-profile-info">
                                                <h4 class="mb-1 text-capitalize">{{ $data['user']->first_name }} {{ $data['user']->last_name }}</h4>

                                                <ul class="list-unstyled user-profile-info">
                                                    <li class="mb-1">
                                                        <span class="fw-semibold me-1">Email:</span>
                                                        <span>
                                                            {{ $data['user']->email }}
                                                        </span>
                                                    </li>
                                                    <li class="mb-1">
                                                        <span class="fw-semibold me-1">Employment ID:</span>
                                                        <span>
                                                            @if(isset($data['user']->profile) && !empty($data['user']->profile))
                                                            {{ $data['user']->profile->employment_id }}
                                                            @else
                                                            -
                                                            @endif
                                                        </span>
                                                    </li>
                                                    <li class="mb-1">
                                                        <span class="fw-semibold me-1">Designation:</span>
                                                        <span>
                                                            @if(isset($data['user']->jobHistory->designation->title) && !empty($data['user']->jobHistory->designation->title))
                                                                {{ $data['user']->jobHistory->designation->title }}
                                                            @else
                                                            -
                                                            @endif
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>

                                            {{-- @if($data['month']==date('m') && date('d') < 24) --}}
                                            @can('generate_pay_slip-create')
                                                @if ($data['user']->employeeStatus->end_date != null)
                                                    @php $monthYear = $data['month'].'/'.$data['year']; @endphp
                                                    @if($monthYear > date('m/Y', strtotime($data['user']->employeeStatus->end_date)))
                                                        @php
                                                            $data['month'] = date('m', strtotime($data['user']->employeeStatus->end_date));
                                                            $data['year'] = date('Y', strtotime($data['user']->employeeStatus->end_date));
                                                        @endphp
                                                    @endif
                                                @endif
                                                @if(!isset($message))
                                                    <a href="{{ URL::to('employees/generate_salary_slip/'.$data['month'].'/'.$data['year'].'/'.$data['user']->slug) }}" target="_blank" class="btn btn-primary waves-effect waves-light"><i class="ti ti-printer me-1"></i>Generate Salary Slip </a>
                                                @endif
                                            @endcan
                                            {{-- @elseif($data['month']!=date('m'))
                                                @can('generate_pay_slip-create')
                                                    <a href="{{ URL::to('employees/generate_salary_slip/'.$data['month'].'/'.$data['year'].'/'.$data['user']->slug) }}" target="_blank" class="btn btn-primary waves-effect waves-light"><i class="ti ti-printer me-1"></i>Generate Salary Slip </a>
                                                @endcan
                                            @endif --}}

                                        </div>
                                    </div>
                                </div>
                            </a>
                        </span>
                    </div>

                    @if(isset($message))
                    <div class="card-datatable table-responsive text-center">
                        <h4>{{ $message }}</h4>
                    </div>
                    @else
                    <div class="card-datatable table-responsive">
                        <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                            <div class="row me-2">
                                <div class="col-md-2">
                                    <div class="me-3">
                                        <div class="dataTables_length" id="DataTables_Table_0_length"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h4 class="text-center">Payslip - {{ $data['month_year'] }}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <table class="table-striped table salary-table">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Employee No.</h6>
                                                </th>
                                                <td class="text-end">
                                                    @if(isset($data['user']->profile) && !empty($data['user']->profile->employment_id))
                                                    {{ $data['user']->profile->employment_id }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Designation</h6>
                                                </th>
                                                <td class="text-end">
                                                    @if(isset($data['user']->jobHistory->designation->title) && !empty($data['user']->jobHistory->designation->title))
                                                    {{ $data['user']->jobHistory->designation->title }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Total Days</h6>
                                                </th>
                                                <td class="text-end"> {{ $data['totalDays'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Per Day Salary</h6>
                                                </th>
                                                <td class="text-end">{{$currency_code ?? "Rs."}} {{ number_format($data['per_day_salary']) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table-striped table salary-table">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Employee Name.</h6>
                                                </th>
                                                <td class="text-end text-capitalize">{{ $data['user']->first_name }} {{ $data['user']->last_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Appointment Date</h6>
                                                </th>
                                                <td class="text-end">
                                                    @if(isset($data['user']->profile) && !empty($data['user']->profile->joining_date))
                                                    {{ date('d M Y', strtotime($data['user']->profile->joining_date)) }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Department</h6>
                                                </th>
                                                <td class="text-end">
                                                    @if(isset($data['user']->departmentBridge->department) && !empty($data['user']->departmentBridge->department->name))
                                                    {{ $data['user']->departmentBridge->department->name }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <h6 class="mb-0">Earning Days</h6>
                                                </th>
                                                <td class="text-end">{{ $data['total_earning_days'] }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <table class="table-striped table mt-3 salary-table">
                                        <thead>
                                            <tr class="py-2">
                                                <th>Title</th>
                                                <th class="text-center">Actual</th>
                                                <th class="text-center">Earning</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Basic Salary</h5>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ number_format($data['salary']) }}</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ number_format($data['earning_days_amount']) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">House Rent
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Medical
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Cost Of Living Allowance
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Special
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Car Allowance
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}}  {{ number_format($data['car_allowance']) }}</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}}  {{ number_format($data['car_allowance']) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Arrears
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Extra Days Amount
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Total Earnings
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ $data['total_actual_salary'] }}</p>
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ $data['total_earning_salary'] }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table-striped table mt-3 salary-table">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th class="text-center">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Absent Days Amount
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ number_format($data['absent_days_amount']) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Half Days Amount
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ number_format($data['half_days_amount']) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Late In + Early Out Amount
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ number_format($data['late_in_early_out_amount']) }}</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Income Tax (will be calculated at the time of salary)
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">EOBI
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Loan Installment
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">Advance Salary
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} 0</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <h6 class="mb-0">NET SALARY
                                                </td>
                                                <td>
                                                    <p class="mb-0 text-center">{{$currency_code ?? "Rs."}} {{ $data['net_salary'] }}</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $(function() {
        var currentMonth = $('#Slipbutton').data('current-month');
        var joiningMonthYear = $('#Slipbutton').data('joining-date');

        $('#Slipbutton').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            startDate: joiningMonthYear,
            endDate: currentMonth,
        }).on('changeMonth', function(e) {
            var employeeSlug = $('#employee-slug option:selected').data('user-slug');
            if (employeeSlug == undefined) {
                employeeSlug = $('#current_user_slug').val();
            }
            var selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            var selectedYear = e.date.getFullYear();

            var selectOptionUrl = "{{ URL::to('employees/salary_details') }}/" + selectedMonth + "/" + selectedYear + "/" + employeeSlug;

            window.location.href = selectOptionUrl;
        });

        const url = new URL(window.location.href);
        const pathname = url.pathname;
        const pathParts = pathname.split('/');
        if (pathParts.length > 5) {
            const emp = pathParts.pop();
            const year = pathParts.pop();
            const month = pathParts.pop();

            $('#Slipbutton').datepicker('setDate', new Date(year, month - 1));
        }
    });

    function redirectPage(dropdown) {
        var selectedOption = dropdown.value;

        if (selectedOption !== '') {
            window.location.href = selectedOption;
        }
    }
</script>
@endpush
</div>
