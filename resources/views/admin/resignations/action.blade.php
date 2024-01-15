<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @if(Auth::user()->id != $data->employee_id && $data->status==0 && Auth::user()->id==$data->hasEmployee->departmentBridge->department->manager_id)
            <a href="javascript:;" class="dropdown-item with-comment-status-btn" data-status-type="approve" data-status-url="{{ route('resignations.status', $data->id) }}">
                Approve
            </a>
            <a href="javascript:;" class="dropdown-item with-comment-status-btn" data-status-type="reject" data-status-url="{{ route('resignations.status', $data->id) }}">
                Reject
            </a>
        @elseif($data->status==1 && Auth::user()->hasRole('Admin'))
            <a href="javascript:;" class="dropdown-item with-comment-status-btn" data-status-type="approve" data-status-url="{{ route('resignations.status', $data->id) }}">
                Approve
            </a>
            <a href="javascript:;" class="dropdown-item with-comment-status-btn" data-status-type="reject" data-status-url="{{ route('resignations.status', $data->id) }}">
                Reject
            </a>
        @endif
        @if(Auth::user()->hasRole('Admin') && $data->is_rehired==0 && $data->status==2 && $data->last_working_date < date('Y-m-d'))
            <a href="#"
                class="dropdown-item edit-btn"
                data-toggle="tooltip"
                data-placement="top"
                title="Re-hire Employee"
                data-edit-url="{{ route('employees.edit', $data->hasEmployee->slug) }}"
                data-url="{{ route('employees.re-hire') }}"
                tabindex="0" aria-controls="DataTables_Table_0"
                type="button" 
                data-bs-toggle="modal"
                data-bs-target="#create-re-hire-form-modal"
                >
                Re-hiring
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
            title="Resignation Details"
            data-show-url="{{ route('resignations.show', $data->id) }}"
            >
            View Details
        </a>
        
        @if(Auth::user()->id == $data->employee_id && $data->status==0)
            @can('resignations-edit')
                <a href="#"
                    data-toggle="tooltip"
                    data-placement="top"
                    title="Edit Resignation"
                    data-edit-url="{{ route('resignations.edit', $data->id) }}"
                    data-url="{{ route('resignations.update', $data->id) }}"
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
            @can('resignations-delete')
                <a href="javascript:;" class="dropdown-item delete" data-del-url="{{ route('resignations.destroy', $data->id) }}">Delete</a>
            @endcan
        @endif
    </div>
</div>
