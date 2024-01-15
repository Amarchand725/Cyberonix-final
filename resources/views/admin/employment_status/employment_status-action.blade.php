<div class="d-flex align-items-center">
    <a href="javascript:;"
        class="btn btn-icon btn-label-info waves-effect edit-btn me-2"
        data-edit-url="{{ route('employment_status.edit', $model->id) }}"
        data-url="{{ route('employment_status.update', $model->id) }}"
        data-toggle="tooltip"
        data-placement="top"
        title="Edit Employment Status"
        tabindex="0" aria-controls="DataTables_Table_0"
        type="button" data-bs-toggle="modal"
        data-bs-target="#create-form-modal"
        >
        <i class="ti ti-edit ti-xs"></i>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Delete Record" href="javascript:;" class="btn btn-icon btn-label-primary waves-effect delete" data-slug="{{ $model->id }}" data-del-url="{{ route('employment_status.destroy', $model->id) }}">
        <i class="ti ti-trash ti-xs"></i>
    </a>
</div>
