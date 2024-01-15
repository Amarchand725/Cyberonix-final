<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="#" class="dropdown-item status-btn" data-status-url='{{ route('departments.status', $model->id) }}'>
            @if($model->status==1)
                De-active
            @else
                Active
            @endif
        </a>
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button" data-bs-toggle="modal"
            data-bs-target="#dept-details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Department Details"
            data-show-url="{{ route('departments.show', $model->id) }}"
            >
            View Details
        </a>
        @can('departments-edit')
            <a href="#"
                class="dropdown-item edit-btn"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Department"
                data-edit-url="{{ route('departments.edit', $model->id) }}"
                data-url="{{ route('departments.update', $model->id) }}"
                tabindex="0"
                aria-controls="DataTables_Table_0"
                type="button"
                data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddDepartment" fdprocessedid="i1qq7b">
                Edit
            </a>
        @endcan
        {{--  <a href="{{ route('logs.showJsonData', ['departments', $model->id]) }}" class="dropdown-item  "  target="_blank"  >View Logs</a>  --}}
        @can('departments-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('departments.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
