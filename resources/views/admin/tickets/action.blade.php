<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @if(Auth::user()->id != $data->user_id && ($data->status != 1 || $data->status != 2) )
        <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('tickets.status', $data->id) }}">
            Approve
        </a>
        @endif
        @if(Auth::user()->id == $data->user_id && ($data->status== 1 || $data->status== 2) )
        <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url="{{ route('tickets.status', $data->id) }}">
            Complete
        </a>
        @endif
        <a href="#" class="dropdown-item show" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#details-modal" data-toggle="tooltip" data-placement="top" title="Ticket Details" data-show-url="{{ route('tickets.show', $data->id) }}">
            View Details
        </a>
        @if(Auth::user()->id == $data->user_id && $data->status==0)
        @can('tickets-edit')
        <a href="#" data-toggle="tooltip" data-placement="top" title="Edit Ticket" data-edit-url="{{ route('tickets.edit', $data->id) }}" data-url="{{ route('tickets.update_ticket') }}" class="dropdown-item edit-btn" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
            Edit Ticket
        </a>
        @endcan
        @can('tickets-delete')
        <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $data->id }}" data-del-url="{{ route('tickets.destroy', $data->id) }}">Delete</a>
        @endcan
        @endif
    </div>
</div>