<div class="d-flex align-items-center">
    <a href="{{ route('office_boys.convert-pdf', $employee->id) }}" class="btn btn-icon btn-label-primary waves-effect" data-toggle="tooltip" data-placement="top" title="Download as PDF">
        <i class="ti ti-download ti-sm"></i>
    </a>
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @can('office_boys-status')
        @if($employee->status==0)
        <a href="javascript:;" class="dropdown-item edit-btn" data-toggle="tooltip" data-placement="top" onClick="selectInit();" title="Approve Office Boy" data-edit-url="{{ route('office_boys.edit', $employee->id) }}" data-url='{{ route('pre_employees.update', $employee->id) }}' tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#create-form-modal">
            Approve
        </a>
        @endif
        @endcan
        @can('office_boys-change-manager')
        <a href="javascript:;" class="dropdown-item getManagers" data-employee-id="{{ $employee->id ?? null }}" data-manager-id="{{ isset($employee->manager_id) && !empty($employee->manager_id) ? $employee->manager_id : null }}">
            Update Manager
        </a>
        @endcan
        <a href="{{ route('office_boys.show', $employee->id) }}" class="dropdown-item">View Details</a>
        @can('office_boys-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $employee->id }}" data-del-url="{{ route('office_boys.destroy', $employee->id) }}">Delete</a>
        @endcan
    </div>
</div>
