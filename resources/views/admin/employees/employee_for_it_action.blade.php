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
</div>
