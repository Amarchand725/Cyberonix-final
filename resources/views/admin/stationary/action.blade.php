<div class="d-flex align-items-center">
    <a href="javascript:;"
        class="text-body dropdown-toggle hide-arrow"
        data-bs-toggle="dropdown"
        aria-expanded="false">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end m-0" style="">
        <a href="#"
            class="dropdown-item show"
            tabindex="0"
            aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Stationary Details"
            data-show-url="{{ route('stationary.show', $model->id) }}">
            View Details
        </a>
        @can('stationary-edit')
            <a href="#"
                class="dropdown-item edit-btn"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Stationary"
                data-edit-url="{{ route('stationary.edit', $model->id) }}"
                data-url="{{ route('stationary.update', $model->id) }}"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#create-form-modal"
                fdprocessedid="i1qq7b">
                Edit
            </a>
        @endcan
        @can('stationary-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('stationary.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
