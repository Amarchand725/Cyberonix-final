<div class="d-flex align-items-center">
    <a href="#"
        data-toggle="tooltip"
        data-placement="top"
        title="Edit Authorize Email"
        onClick="selectInit();"
        data-edit-url="{{ route('authorize_emails.edit', $model->id) }}"
        data-url="{{ route('authorize_emails.update', $model->id) }}"
        class="btn btn-icon btn-label-info waves-effect me-2 edit-btn"
        type="button"
         aria-controls="DataTables_Table_0"
        type="button" data-bs-toggle="modal"
        data-bs-target="#offcanvasAddAnnouncement"
        fdprocessedid="i1qq7b">
        <i class="ti ti-edit ti-xs"></i>
    </a>
    {{--  <a href="{{ route('logs.showJsonData', ['authorize-emails', $model->id]) }}" class="btn btn-label-warning waves-effect"  target="_blank"  >View Logs</a>  --}}
    <a href="javascript:;" class="delete btn btn-icon btn-label-primary waves-effect" style="margin-left: 8px;" data-slug="{{ $model->id }}" data-del-url="{{ route('authorize_emails.destroy', $model->id) }}">
        <i class="ti ti-trash ti-xs"></i>
    </a>
</div>
