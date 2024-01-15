<table class="table table-bordered table-striped">
    <tr>
        <th>Vehicle</th>
        <td>
           <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasVehicle->thumbnail))
                            <img src="{{ asset('public/upload/vehicle/thumbnails') }}/{{ $model->hasVehicle->thumbnail }}" alt="Avatar" class="rounded-circle img-avatar">
                        @else
                            <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    @if(!empty($model->hasVehicle->name))
                        <span class="fw-semibold">{{ $model->hasVehicle->name }} ({{ $model->hasVehicle->color }})</span>
                        <small class="text-muted">{{ $model->hasVehicle->model }} ({{ $model->hasVehicle->model_year }})</small>
                    @else
                    -
                    @endif
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>User</th>
        <td>
           <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasUser->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar">
                        @else
                            <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    @if(!empty($model->hasUser->first_name))
                        <a href="{{ route('employees.show', $model->hasUser->slug) }}" class="text-body text-truncate">
                            <span class="fw-semibold">{{ $model->hasUser->first_name }} {{ $model->hasUser->last_name }}</span>
                        </a>
                        <small class="text-muted">{{ $model->hasUser->email }}</small>
                    @endif
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Effective Date</th>
        <td>
            @if(!empty($model->receive_date))
                {{ date('d M Y', strtotime($model->receive_date)) }}
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <th>Deliver Date</th>
        <td>
            @if(!empty($model->delivery_date))
                {{ date('d M Y', strtotime($model->delivery_date)) }}
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <th>End Date</th>
        <td>
            @if(!empty($model->end_date))
                <span class="text-primary">{{ date('d M Y', strtotime($model->end_date)) }}</span>
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($model->created_at)) }}</td>
    </tr>
    <tr>
        <th>Delivery Details</th>
        <td>{{ $model->delivery_details??'-' }}</td>
    </tr>
    <tr>
        <th>Inspection Details</th>
        <td>{{ $model->inspection_details??'-' }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>
            @if($model->status)
                <span class="badge bg-label-success" text-capitalized="">Active</span>
            @else
                <span class="badge bg-label-danger" text-capitalized="">De-Active</span>
            @endif
        </td>
    </tr>
</table>
