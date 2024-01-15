<div class="d-flex justify-content-start align-items-center user-name">
    @if(isset($employee->hasManager) && !empty($employee->hasManager->first_name))
        <div class="avatar-wrapper">
            <div class="avatar avatar-sm me-3">
                @if(!empty($employee->hasManager->profile->profile))
                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$employee->hasManager->profile->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar">
                @else
                    <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
                @endif
            </div>
        </div>
        <div class="d-flex flex-column">
            <a href="{{ route('employees.show', $employee->hasManager->slug) }}" class="text-body text-truncate">
                <span class="fw-semibold">{{ $employee->hasManager->first_name }} {{ $employee->hasManager->last_name }}</span>
            </a>
            <small class="text-muted">{{ $employee->hasManager->email }}</small>
        </div>
    @else
    -
    @endif
</div>
