<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-xs mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @php
            $month_year = explode('/', $employee->month_year);
            $month = $month_year[0];
            $year = date('Y', strtotime($month_year[1]));
        @endphp
        <a href="{{ URL::to('employees/salary_details/'.$month.'/'.$year.'/'.$employee->hasEmployee->slug) }}" target="_blank" class="dropdown-item">Salary Slip </a>
    </div>
</div>
