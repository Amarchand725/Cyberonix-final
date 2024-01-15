<div class="d-flex align-items-center">
    <a href="javascript:;"
        class="btn btn-icon btn-label-info waves-effect me-2 edit-btn"
        data-toggle="tooltip" data-placement="top"
        title="Edit Leave Type"
        tabindex="0" aria-controls="DataTables_Table_0" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvasmodal"
        data-edit-url="{{ route('leave_types.edit', $model->id) }}"
        data-url="{{ route('leave_types.update', $model->id) }}">
        <i class="ti ti-edit ti-xs"></i>
    </a>
    <a href="javascript:;"
        data-toggle="tooltip"
        data-placement="top"
        title="Delete Leave Type"
        class="delete btn btn-icon btn-label-primary waves-effect"
        data-slug="{{ $model->id }}"
        data-del-url="{{ route('leave_types.destroy', $model->id) }}"
    >
        <i class="ti ti-trash ti-xs"></i>
    </a>
</div>
