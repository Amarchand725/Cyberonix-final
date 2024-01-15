<div class="d-flex align-items-center">
    <a href="javascript:;"
        class="edit-btn btn btn-icon btn-label-info waves-effect"
        data-toggle="tooltip"
        data-placement="top"
        title="Edit Employee"
        data-edit-url="{{ route('employees.edit', $employee->slug) }}"
        data-url="{{ route('employees.update', $employee->slug) }}"
        tabindex="0" aria-controls="DataTables_Table_0"
        type="button" data-bs-toggle="modal"
        data-bs-target="#create-form-modal"
        >
        <i class="ti ti-edit ti-xs"></i>
    </a>
    <a href="javascript:;"
        class="btn btn-icon btn-label-warning waves-effect edit-btn mx-2"
        data-toggle="tooltip"
        data-placement="top"
        title="Direct User Permissions"
        data-edit-url="{{ route('user-direct.permission.edit', $employee->slug) }}"
        data-url="{{ route('user-direct.permission.update', $employee->slug) }}"
        tabindex="0" aria-controls="DataTables_Table_0"
        type="button" data-bs-toggle="modal"
        data-bs-target="#edit-direct-permission-modal"
        >
        <i class="ti ti-lock ti-xs"></i>
    </a>
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-xs mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @canImpersonate($guard = null)
            <a href="javascript:;" class="dropdown-item impersonate-btn" data-url="{{ route('impersonate', $employee->id) }}">
                Impersonate
            </a>
        @endCanImpersonate
        <a href="javascript:;" class="dropdown-item emp-status-btn" data-status-type="status" data-status-url="{{ route('employees.status', $employee->id) }}">
            @if($employee->status)
                De-Active
            @else
                Active
            @endif
        </a>
        @php
            $salary = '';
            $effect_date = '';
            if(isset($employee->jobHistory->salary) && !empty($employee->jobHistory->salary->salary)){
                $salary = $employee->jobHistory->salary->salary;
                $effect_date = $employee->jobHistory->salary->effective_date;
            }
        @endphp
        <a href="{{ route('employees.show', $employee->slug) }}" class="dropdown-item">View Details</a>

        <a href="javascript:;"
            class="dropdown-item promote-employee-btn"
            data-title="Add/Update Work Shift"
            data-user-id="{{ $employee->id }}"
            data-edit-url="{{ route('get_work_shifts') }}"
            data-url='{{ route("store_work_shift") }}'
            onclick="selectInit();">
            Add Shift
        </a>
        @if(!empty($employee->employeeStatus->employmentStatus->name) && $employee->employeeStatus->employmentStatus->name == 'Probation')
            <a href="javascript:;"
                class="dropdown-item status-btn"
                data-title="Permanent"
                data-status-url='{{ route("employee-permanent", $employee->id) }}'>
                Permanent
            </a>
        @else
            <a href="javascript:;"
                class="dropdown-item promote-employee-btn"
                data-title="Employee Promotion"
                data-user-id="{{ $employee->id }}"
                data-edit-url="{{ route('get_promote_data') }}"
                data-url='{{ route("store_promote") }}'
                onclick="selectInit();">
                Promotion
            </a>
        @endif
        <a
            href="javascript:;"
            data-toggle="tooltip"
            data-placement="top"
            title="Add Termination"
            type="button"
            class="dropdown-item terminate-emp add-btn"
            id="terminate-employee"
            data-user-id="{{ $employee->id }}"
            data-url="{{ route('resignations.store') }}"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button" data-bs-toggle="modal"
            data-bs-target="#terminate-employee-modal"
            >
            Terminate
        </a>
        {{--  <a href="{{ route('logs.showJsonData', ['employees', $model->id]) }}" class="btn btn-label-warning waves-effect"  target="_blank"  >View Logs</a>  --}}
        <a href="javascript:;" class="dropdown-item emp-status-btn" data-status-type="remove" data-status-url='{{ route('employees.status', $employee->id) }}'>Remove from employee list</a>
        @can('employees-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $employee->id }}" data-del-url="{{ route('employees.destroy', $employee->id) }}">Delete</a>
        @endcan
    </div>
</div>
