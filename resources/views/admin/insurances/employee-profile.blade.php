@if(isset($employee->hasUser))
    <div class="d-flex justify-content-start align-items-center user-name">
        <div class="avatar-wrapper">
            <div class="avatar avatar-sm me-3">
                @if(!empty($employee->hasUser->profile->profile))
                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$employee->hasUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                @else
                    <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                @endif
            </div>
        </div>
        <div class="d-flex flex-column">
            <a href="{{ route('employees.show', $employee->hasUser->slug) }}" class="text-body text-truncate">
                <span class="fw-semibold">{{ $employee->name_as_per_cnic }}</span>
            </a>
        </div>
    </div>
@else
    <span class="text-capitalize text-primary">{{ $employee->name_as_per_cnic }}</span>
@endif
