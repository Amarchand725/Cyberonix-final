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
            data-bs-target="#vehicles-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Owner Vehicles"
            data-show-url="{{ route('vehicle_owners.vehicles', $model->id) }}"
            >
            Owner Vehicles
        </a>
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Owner Details"
            data-show-url="{{ route('vehicle_owners.show', $model->id) }}"
            >
            View Details
        </a>
        @can('vehicle_owners-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Vehicle User"
                data-edit-url="{{ route('vehicle_owners.edit', $model->id) }}"
                data-url="{{ route('vehicle_owners.update', $model->id) }}"
                class="dropdown-item edit-btn"
                type="button"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#offcanvasAddAnnouncement"
                >
                Edit Owner
            </a>
        @endcan
        @can('vehicle_owners-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('vehicle_owners.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
