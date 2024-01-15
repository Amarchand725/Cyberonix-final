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
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Vehicle Inspection History"
            data-show-url="{{ route('vehicle_users.vehicle_inspection.history', ['vehicle_id' => $model->vehicle_id, 'user_id' => $model->user_id]) }}"
            >
            Inspection History
        </a>
        @if(Auth::user()->hasRole('Admin'))
            <a href="#"
                class="dropdown-item shareBtn"
                tabindex="0" aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#share-modal"
                data-toggle="tooltip"
                data-placement="top"
                title="Share Vehicle"
                data-vehicle-user-id="{{ $model->id }}"
                data-url="{{ route('vehicle_users.share_vehicle') }}"
                >
                Share Vehicle
            </a>
        @endif
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button"
            data-bs-toggle="modal"
            data-bs-target="#details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Vehicle User Details"
            data-show-url="{{ route('vehicle_users.show', $model->id) }}"
            >
            View Details
        </a>
        @can('vehicle_users-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Vehicle User"
                data-edit-url="{{ route('vehicle_users.edit', $model->id) }}"
                data-url="{{ route('vehicle_users.update', $model->id) }}"
                class="dropdown-item edit-btn"
                type="button"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="modal"
                data-bs-target="#offcanvasAddAnnouncement"
                >
                Edit Vehicle User
            </a>
        @endcan
        @can('vehicle_users-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('vehicle_users.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
