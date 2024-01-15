<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($model->hasUser->profile->profile))
                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <a href="{{ route('employees.show', $model->hasUser->slug) }}" class="text-body text-truncate">
            <span class="fw-semibold">{{ $model->hasUser->first_name }} {{ $model->hasUser->last_name }}</span>
        </a>
        <small class="text-muted">{{ $model->hasUser->email }}</small>
    </div>
</div>
