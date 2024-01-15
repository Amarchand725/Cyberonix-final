<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @php $bool = false @endphp 
        
        @can('allow_approve_additional_discrepancies-create')
            @php $bool = true @endphp
        @endcan
        
        @if($model->is_additional==0 && Auth::user()->hasRole('Department Manager'))
            @if($model->status==0 || $model->status==2)
                <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('user.discrepancy.status', ['id' => $model->id, 'status' => 'approve']) }}">
                    Approve
                </a>
            @endif
            @if($model->status==0)
                <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('user.discrepancy.status', ['id' => $model->id, 'status' => 'reject']) }}">
                    Reject
                </a>
            @endif
        @elseif($model->is_additional==1 && $model->status==0 && Auth::user()->hasRole('Admin') || $bool)
            <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('user.discrepancy.status', ['id' => $model->id, 'status' => 'approve']) }}">
                Approve  
            </a>
            <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('user.discrepancy.status', ['id' => $model->id, 'status' => 'reject']) }}">
                Reject
            </a>
        @endif
        
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