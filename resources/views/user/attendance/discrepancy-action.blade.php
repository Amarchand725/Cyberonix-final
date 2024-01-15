<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a
            data-toggle="tooltip"
            data-placement="top"
            title="Discrepancy Details"
            type="button"
            class="dropdown-item show"
            data-show-url="{{ route('user.discrepancy.show', $model->id) }}"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button" data-bs-toggle="modal"
            data-bs-target="#view-discrepancy-details-modal"
            >
            View Details
        </a>
    </div>
</div>
