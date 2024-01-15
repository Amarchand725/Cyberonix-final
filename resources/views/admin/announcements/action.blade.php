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
            title="Announcement Details"
            data-show-url="{{ route('announcements.show', $model->id) }}"
            >
            View Details
        </a>
        @can('announcements-edit')
            <a href="#"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Announcement"
                data-edit-url="{{ route('announcements.edit', $model->id) }}"
                data-url="{{ route('announcements.update', $model->id) }}"
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
        {{--  <a href="{{ route('logs.showJsonData', ['announcements',$model->id,isset($model->title) && !empty($model->title) ? Str::slug($model->title) : '']) }}" class="dropdown-item  "  target="_blank"  >View Logs</a>  --}}
        @can('announcements-delete')
            <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('announcements.destroy', $model->id) }}">Delete</a>
        @endcan
    </div>
</div>
