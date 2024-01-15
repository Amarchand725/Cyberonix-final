<div class="d-flex align-items-center">
    <a href="javascript:;" class="text-body dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical ti-sm mx-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end m-0">
        <a href="{{ route('employees.show', $employee->slug) }}" class="dropdown-item">View Details</a>

        @if(Auth::user()->hasRole('Admin'))
            <a href="javascript:;" class="dropdown-item emp-status-btn" data-status-type="status" data-status-url='{{ route('employees.status', $employee->id) }}'>
                @if($employee->status)
                    De-Active
                @else
                    Active
                @endif
            </a>
        @endif
    </div>
</div>
