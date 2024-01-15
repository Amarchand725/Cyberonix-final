<div class="d-flex align-items-center">
    <a data-toggle="tooltip" data-placement="top" title="Delete Record" href="javascript:;" class="btn btn-icon btn-label-primary waves-effect delete" data-slug="{{ $model->label }}" data-del-url="{{ route('permissions.destroy', $model->label) }}">
        <i class="ti ti-trash ti-xs mx-2"></i>
    </a>
</div>
