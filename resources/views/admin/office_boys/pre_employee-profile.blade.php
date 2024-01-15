<div class="d-flex justify-content-start align-items-center user-name">
    <div class="d-flex flex-column">
        <a href="{{ route('pre_employees.show', $employee->id) }}" class="text-body text-truncate">
            <span class="fw-semibold">{{ $employee->name??'' }}</span>
        </a>
        <small class="text-muted">{{ $employee->email??'-' }}</small>
    </div>
</div>
