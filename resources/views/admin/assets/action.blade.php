<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Add More Quantity" data-edit-url="{{ route('assets.addMore', $model->id) }}" data-url="{{ route('assets.addMoreUpdate', $model->id) }}" class="dropdown-item add-more-btn" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
            Add more Quantity
        </a> -->
        <a href="{{route('assets.show' , $model->id)}}" data-toggle="tooltip" data-placement="top" title="View Details" class="dropdown-item  ">
            View Details
        </a>
        <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Assign to an employee" data-edit-url="{{ route('assets.assign', $model->id) }}" data-url="{{ route('assets.assignUpdate', $model->id) }}" class="dropdown-item assign-asset" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
            Assign
        </a> -->
        <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="Edit Asset" data-edit-url="{{ route('assets.edit', $model->id) }}" data-url="{{ route('assets.update', $model->id) }}" class="dropdown-item edit-btn" type="button" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
            Edit
        </a> -->
        <a href="javascript:;" class="dropdown-item delete" data-slug="{{ $model->id }}" data-del-url="{{ route('assets.destroy', $model->id) }}">Delete</a>
    </div>
</div>