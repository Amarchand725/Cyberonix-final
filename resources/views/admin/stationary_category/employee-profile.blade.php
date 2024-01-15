@if(isset($model->employee) && !empty($model->employee->first_name))
    <div class="d-flex justify-content-start align-items-center user-name">
        <div class="avatar-wrapper">
            <div class="avatar avatar-sm me-3">
                @if(!empty($model->employee->profile->profile))
                    <img src="{{ asset('public/admin/assets/img/avatars') }}/{{ $model->employee->profile->profile }}" alt="Avatar" class="rounded-circle">
                @else
                    <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                @endif
            </div>
        </div>
        <div class="d-flex flex-column">
            <a href="{{ route('employees.show', $model->employee->slug) }}" class="text-body text-truncate">
                <span class="fw-semibold">{{ $model->employee->first_name??'' }} {{ $model->employee->last_name??'' }}</span>
            </a>
            <small class="text-muted">{{ $model->employee->email??'-' }}</small>
        </div>
    </div>
@else
-
@endif
