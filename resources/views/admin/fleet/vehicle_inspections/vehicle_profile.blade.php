<div class="d-flex justify-content-start align-items-center user-name">
    <div class="avatar-wrapper">
        <div class="avatar avatar-sm me-3">
            @if(!empty($model->hasVehicle->thumbnail))
                <img src="{{ asset('public/upload/vehicle/thumbnails') }}/{{ $model->hasVehicle->thumbnail }}" alt="Avatar" class="rounded-circle" style="object-fit: cover !important;">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle" style="object-fit: cover !important;">
            @endif
        </div>
    </div>
    <div class="d-flex flex-column">
        @if(!empty($model->hasVehicle->name))
            <span class="fw-semibold text-primary">{{ $model->hasVehicle->name }} ({{ $model->hasVehicle->color }})</span>
            <small class="text-muted">{{ $model->hasVehicle->model }} ({{ $model->hasVehicle->model_year }})</small>
        @else
        -
        @endif
    </div>
</div>
