<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="javascript:;" class="dropdown-item status-btn" data-status-type="status" data-status-url='{{ route('profile_cover_images.status', $model->id) }}'>
            @if($model->status)
                De-Active
            @else
                Active
            @endif
        </a>
        <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('profile_cover_images.destroy', $model->id) }}">Delete</a>
    </div>
</div>
