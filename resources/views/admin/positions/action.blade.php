<div class="d-flex align-items-center">
    <a href="javascript:;"
        class="btn btn-icon btn-label-info waves-effect me-2 edit-btn"
        data-toggle="tooltip" data-placement="top"
        title="Edit Position"
        tabindex="0" aria-controls="DataTables_Table_0" type="button"
        data-bs-toggle="offcanvas" data-bs-target="#offcanvasmodal"
        data-edit-url="{{ route('positions.edit', $data->id) }}"
        data-url="{{ route('positions.update', $data->id) }}"
        >
        <i class="ti ti-edit ti-xs"></i>
    </a>
    <a href="javascript:;"
        data-toggle="tooltip"
        data-placement="top"
        title="Delete Record"
        class="btn btn-icon btn-label-primary waves-effect delete"
        data-del-url="{{ route('positions.destroy', $data->id) }}">
        <i class="ti ti-trash ti-xs"></i>
    </a>
</div>
