<div class="d-flex align-items-center">
    @can('stationary-list')
        <a href="{{ route('stationary.list', $model->id) }}"
            class="btn btn-icon btn-label-info waves-effect me-2"
            data-toggle="tooltip"
            data-placement="top"
            title="List Stationaries"
            >
            <i class="ti ti-list ti-xs"></i>
        </a>
    @endcan

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
            title="Stationary Category Details"
            data-show-url="{{ route('stationary_categories.show', $model->id) }}">
            View Details
        </a>

        @can('stationary_category-edit')
            <a href="#"
                class="dropdown-item edit-btn"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Stationary Category"
                data-edit-url="{{ route('stationary_categories.edit', $model->id) }}"
                data-url="{{ route('stationary_categories.update', $model->id) }}"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#create-form-modal"
                fdprocessedid="i1qq7b">
                Edit
            </a>
        @endcan

        @can('stationary_category-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('stationary_categories.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
