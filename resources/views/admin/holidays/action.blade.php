<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a
            data-toggle="tooltip"
            data-placement="top"
            type="button"
            class="dropdown-item"
            href="{{ route('holidays.show', $model->id) }}"
            >
            View Details
        </a>

        @can('holidays-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Holiday Details"
                data-edit-url="{{ route('holidays.edit', $model->id) }}"
                data-url="{{ route('holidays.update', $model->id) }}"
                class="dropdown-item edit-btn"
                type="button"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#offcanvasAddAnnouncement"
                >
                Edit
            </a>
        @endcan

        @can('holidays-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('holidays.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
