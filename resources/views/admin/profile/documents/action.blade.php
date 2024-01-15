<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="#" class="dropdown-item show" tabindex="0" aria-controls="DataTables_Table_0" type="button"
            data-bs-toggle="modal" data-bs-target="#details-modal" data-toggle="tooltip" data-placement="top"
            title="View Documents" data-show-url="{{ route('profile.viewDocuments', $model->id) }}">
            View Details
        </a>
        <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="Edit Attachments"
            data-edit-route="{{ route('profile.editDocuments', $model->id) }}" class="dropdown-item edit-doc-btn"
            data-id="{{ $model->id }}">
            Edit
        </a>
        <a href="javascript:;" class="dropdown-item  delete-document-with-attachment"
            data-route="{{ route('profile.deleteDocumentWithAttachments', $model->id) }}" data-count="{{ $model->id }}">Delete</a>
    </div>
</div>
