<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($employee->profile->profile))
                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$employee->profile->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('employees.show', $employee->slug) }}" class="text-body text-truncate">
            <span class="fw-semibold">{{ $employee->first_name??'' }} {{ $employee->last_name??'' }} ({{ $employee->profile->employment_id??'-' }})</span>
        </a>
        <small class="emp_post text-truncate text-muted">
            @if(isset($employee->jobHistory->designation) && !empty($employee->jobHistory->designation->title))
                {{ $employee->jobHistory->designation->title }}
            @else
            -
            @endif
        </small>
    </div>
</div>
