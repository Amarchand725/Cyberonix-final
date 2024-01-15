<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#history-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Vehicle Inspection History"
            data-show-url="{{ route('vehicles.inspections.history', $model->id) }}"
            >
            Inspection History
        </a>
         <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#history-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Vehicle User History"
            data-show-url="{{ route('vehicles.users.history', $model->id) }}"
            >
            User History
        </a>
         <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Vehicle Details"
            data-show-url="{{ route('vehicles.show', $model->id) }}"
            >
            View Details
        </a>
        @can('vehicles-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Vehicle"
                data-edit-url="{{ route('vehicles.edit', $model->id) }}"
                data-url="{{ route('vehicles.update_vehicle') }}"
                class="dropdown-item edit-btn"
                type="button"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#offcanvasAddAnnouncement"
                >
                Edit Vehicle
            </a>
        @endcan
        @can('vehicles-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('vehicles.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
