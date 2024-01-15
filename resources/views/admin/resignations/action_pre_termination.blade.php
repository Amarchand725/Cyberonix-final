<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Resignation Details"
            data-show-url="{{ route('resignations.show', $data->id) }}"
            >
            View Details
        </a>
        @php
        $lastMonth = date('m', strtotime($data->resignation_date));
        $lastYear = date('Y', strtotime($data->resignation_date));
        $employeeSlug = $data->hasEmployee->slug;
        @endphp
        <a href="{{ URL::to('user/attendance/terminated_employee_summary/'.$lastMonth.'/'.$lastYear.'/'.$employeeSlug) }}" class="dropdown-item">Attendance Summary</a>
    </div>
</div>
