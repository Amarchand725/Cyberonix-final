<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-xs mx-1"></i>
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
            title="Insurance Family Data"
            data-show-url="{{ route('insurances.show', $data->id) }}"
            >
            View Details
        </a>
        @can('insurances-edit')
            <!--<a href="#"-->
            <!--    class="dropdown-item edit-btn"-->
            <!--    data-toggle="tooltip"-->
            <!--    data-placement="top"-->
            <!--    title="Edit Insurance Details"-->
            <!--    data-edit-url="{{ route('insurances.edit', $data->id) }}"-->
            <!--    data-url="{{ route('insurances.update', $data->id) }}"-->
            <!--    type="button"-->
            <!--    tabindex="0" aria-controls="DataTables_Table_0"-->
            <!--    type="button" data-bs-toggle="modal"-->
            <!--    data-bs-target="#create-form-modal"-->
            <!--    >-->
            <!--    Edit Details-->
            <!--</a>-->
            
            <a href="{{ route('insurances.edit', $data->id) }}" class="dropdown-item">
                Edit
            </a>
        @endcan
        @can('insurances-delete')
            <a href="javascript:;"
                class="dropdown-item delete"
                data-del-url="{{ route('insurances.destroy', $data->id) }}">
                Delete
            </a>
        @endcan
    </div>
</div>
