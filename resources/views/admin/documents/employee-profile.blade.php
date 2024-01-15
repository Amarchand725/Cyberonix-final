<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($employee->hasEmployee->profile->profile))
                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$employee->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        @if(!empty($employee->hasEmployee))
            <a href="{{ route('employees.show', $employee->hasEmployee->slug) }}" class="text-body text-truncate">
                <span class="fw-semibold">{{ $employee->hasEmployee->first_name??'' }} {{ $employee->hasEmployee->last_name??'' }} ({{ $employee->hasEmployee->profile->employment_id??'-' }})</span>
            </a>
        @endif
        <small class="emp_post text-truncate text-muted">
            @if(isset($employee->hasEmployee->jobHistory->designation) && !empty($employee->hasEmployee->jobHistory->designation->title))
                {{ $employee->hasEmployee->jobHistory->designation->title }}
            @else
            -
            @endif
        </small>
    </div>
</div>
