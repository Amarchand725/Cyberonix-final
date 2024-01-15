<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="#" data-toggle="tooltip" data-placement="top" title="Edit Category" data-edit-url="{{ route('inventory-category.edit', $model->id) }}" data-url="{{ route('inventory-category.update', $model->id) }}" class="dropdown-item edit-btn" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
            Edit
        </a>
        <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('inventory-category.destroy', $model->id) }}">Delete</a>
    </div>
</div>