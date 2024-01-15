<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        @can('bank_accounts-status')
            <a href="#" class="dropdown-item status-btn" data-status-url='{{ route("bank_accounts.status", $model->id) }}'>
                @if($model->status==1)
                    De-active
                @else
                    Active
                @endif
            </a>
        @endcan
        <a href="#"
            class="dropdown-item show"
            tabindex="0" aria-controls="DataTables_Table_0"
            type="button" data-bs-toggle="modal"
            data-bs-target="#dept-details-modal"
            data-toggle="tooltip"
            data-placement="top"
            title="Bank Account Details"
        data-show-url="{{ route('bank_accounts.show', $model->id) }}"
            >
            View Details
        </a>
        @can('bank_accounts-edit_user_account')
            <a href="{{ route('bank_accounts.edit', $model->id) }}" class="dropdown-item">Edit</a>
        @endcan
    </div>
</div>
