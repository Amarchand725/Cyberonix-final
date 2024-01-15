<div class="d-flex align-items-center">
    <a href="javascript:;"
       class="btn btn-icon btn-view btn-label-secondary waves-effect me-2 show"
       data-toggle="tooltip"
       data-placement="top"
       title=""
       data-show-url="{{ route('employee_letters.show', $model->id) }}"
       type="button"
       tabindex="0" aria-controls="DataTables_Table_0"
       type="button" data-bs-toggle="modal"
       data-bs-target="#view-template-modal"
       fdprocessedid="i1qq7b">
       <i class="ti ti-eye ti-xs"></i>
   </a>
   <a href="{{ route('employee_letters.download', $model->id) }}"
       class="btn btn-icon btn-label-info waves-effect me-2"
       data-toggle="tooltip"
       data-placement="top"
       title="Download Letter"
       type="button"
       tabindex="0" aria-controls="DataTables_Table_0"
       type="button" >
       <i class="ti ti-download ti-xs"></i>
   </a>
    @if($model->title=="joining_letter")
        @can('employee_letters-edit')
            <a href="javascript:;"
                class="btn btn-icon btn-label-warning waves-effect me-2 edit-btn"
                data-toggle="tooltip"
                data-placement="top"
                title="Edit Letter"
                data-edit-url="{{ route('employee_letters.edit', $model->id) }}"
                data-url="{{ route('employee_letters.update', $model->id) }}"
                type="button"
                tabindex="0" aria-controls="DataTables_Table_0"
                type="button" data-bs-toggle="modal"
                data-bs-target="#offcanvasAddAnnouncement"
                fdprocessedid="i1qq7b">
                <i class="ti ti-edit ti-xs"></i>
            </a>
        @endcan
    @endif
    {{--  <a href="{{ route('logs.showJsonData', ['employee-letters', $model->id]) }}" class="btn btn-label-warning waves-effect"  target="_blank"  >Logs</a>  --}}
    @can('employee_letters-delete')
        <a href="javascript:;" class="delete btn btn-icon btn-label-primary waves-effect" data-slug="{{ $model->id }}" data-del-url="{{ route('employee_letters.destroy', $model->id) }}"  style="margin-left: 8px;">
            <i class="ti ti-trash ti-xs"></i>
        </a>
    @endcan
</div>
