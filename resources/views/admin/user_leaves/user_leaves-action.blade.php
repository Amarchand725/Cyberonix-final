<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @if(isset($department_manager) && !empty($department_manager) && $model->status==0 && Auth::user()->id==$department_manager->id)
            <a href="javascript:;" class="dropdown-item change-status-btn" data-status-type="status" data-status-url="{{ route('user.leaves.status', ['id' => $model->id, 'status' => 'approve']) }}">
                Approve
            </a>
            <a href="javascript:;" class="dropdown-item change-status-btn" data-status-type="status" data-status-url="{{ route('user.leaves.status', ['id' => $model->id, 'status' => 'reject']) }}">
                Reject
            </a>
        @endif
        <a
            data-toggle="tooltip"
            data-placement="top"
            title="Leave Details"
            type="button"
            class="dropdown-item show"
            data-show-url="{{ route('user_leaves.show', $model->id) }}"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button" data-bs-toggle="modal"
            data-bs-target="#view-leave-details-modal"
            >
            View Details
        </a>
        @if($model->status==0 && Auth::user()->id==$model->user_id)
            @can('tickets-delete')
                <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('user_leaves.destroy', $model->id) }}">Delete</a>
            @endcan
        @endif
    </div>
</div>
