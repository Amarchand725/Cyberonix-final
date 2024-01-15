<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(isset($notification->data['profile']) && !empty($notification->data['profile']))
                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$notification->data['profile'], null) }}" alt="Avatar" class="rounded-circle">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="fw-semibold text-body text-truncate">{{ Str::ucfirst($notification->data['name']) }}</span>
        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
    </div>
</div>
