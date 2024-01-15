<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($model->thumbnail))
                <img src="{{ asset('public/upload/vehicle/thumbnails') }}/{{ $model->thumbnail }}" alt="Avatar" class="rounded-circle" style="object-fit:cover;">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle" style="object-fit:cover;">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        <span class="fw-semibold text-primary">{{ $model->name ?? null }} ({{ $model->color ?? null }})</span>
        <small class="text-muted">{{ $model->model ?? null }} ({{ $model->model_year ?? null }})</small>
    </div>
</div>
