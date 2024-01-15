<div class="d-flex align-items-center">
    <a href="javascript:;"
        class="btn btn-icon btn-label-info waves-effect mx-2 edit-btn"
        data-toggle="tooltip"
        data-placement="top"
        title="Edit Shift"
        data-edit-url="{{ route('work_shifts.edit', $model->id) }}"
        data-url="{{ route('work_shifts.update', $model->id) }}"
        tabindex="0" aria-controls="DataTables_Table_0"
        type="button" data-bs-toggle="modal"
        data-bs-target="#create-form-modal"
        >
        <i class="ti ti-edit ti-xs"></i>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Delete Record" href="javascript:;" class="btn btn-icon btn-label-primary waves-effect delete" data-slug="{{ $model->id }}" data-del-url="{{ route('work_shifts.destroy', $model->id) }}">
        <i class="ti ti-trash ti-xs"></i>
    </a>
</div>
