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
            data-show-url="{{ route('vehicle_users.vehicle_inspection.history', ['vehicle_id' => $model->vehicle_id, 'user_id' => $model->vehicle_user_id]) }}"
            >
            Inspection History
        </a>
         <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Vehicle Inspection Details"
            data-show-url="{{ route('vehicle_inspections.show', $model->id) }}"
            >
            View Details
        </a>
        @can('vehicle_inspections-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Vehicle"
                data-edit-url="{{ route('vehicle_inspections.edit', $model->id) }}"
                data-url="{{ route('vehicle_inspections.update', $model->id) }}"
                class="dropdown-item edit-btn"
                type="button"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#offcanvasAddAnnouncement"
                >
                Edit Inspection
            </a>
        @endcan
        @can('vehicle_inspections-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('vehicle_inspections.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
