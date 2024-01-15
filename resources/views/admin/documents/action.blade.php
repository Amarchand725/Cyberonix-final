<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="#" class="dropdown-item show" tabindex="0" aria-controls="DataTables_Table_0" type="button"
            data-bs-toggle="modal" data-bs-target="#details-modal" data-toggle="tooltip" data-placement="top"
            title="Document Attachments" data-show-url="{{ route('documents.show', $model->id) }}">
            View Details
        </a>
        @can('documents-edit')
            <a href="javascript:;" data-toggle="tooltip" data-placement="top" title="Edit Attachments"
                class="dropdown-item editDocumentHrBtn" data-route="{{ route('documents.edit', $model->id) }}"
                data-id="{{ $model->id }}" onclick="selectInit()">
                Edit
            </a>
        @endcan
        @can('documents-delete')
            <a href="javascript:;" class="dropdown-item  delete-document-with-attachment"
                data-route="{{ route('documents.deleteDocumentWithAttachmentsHr', $model->id) }}"
                data-count="{{ $model->id }}">Delete</a>
        @endcan
    </div>
</div>
