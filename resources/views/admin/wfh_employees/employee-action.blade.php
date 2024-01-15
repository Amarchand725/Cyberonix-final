<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-xs mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('wfh_employees.status', $model->id) }}">
            @if($model->status)
                De-Active
            @else
                Active
            @endif
        </a>
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="WFH Employee Details"
            data-show-url="{{ route('wfh_employees.show', $model->id) }}"
            >
            View Details
        </a>
        @can('wfh_employee-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit WFH Employee"
                data-edit-url="{{ route('wfh_employees.edit', $model->id) }}"
                data-url="{{ route('wfh_employees.update', $model->id) }}"
                class="dropdown-item edit-btn"
                type="button"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#create-form-modal"
                >
                Edit
            </a>
        @endcan
        @can('wfh_employee-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('wfh_employees.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
