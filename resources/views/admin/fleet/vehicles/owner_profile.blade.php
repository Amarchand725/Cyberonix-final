<div class="d-flex justify-content-start align-items-center user-name">
    <div class="d-flex flex-column">
        @if(!empty($model->hasOwner->name))
            <span class="fw-semibold">{{ $model->hasOwner->name }} </span>
            <small class="text-muted">{{ $model->hasOwner->email }}</small>
        @else
        -
        @endif
    </div>
</div>
