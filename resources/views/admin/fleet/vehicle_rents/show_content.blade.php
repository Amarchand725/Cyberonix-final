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
                    @if(isset($model->hasVehicle) && !empty($model->hasVehicle))
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
        <th>Rent (PKR)</th>
        <td><span class="fw-semibold">PKR. {{ number_format($model->rent) }}</span></td>
    </tr>
    <tr>
        <th>Effective Date</th>
        <td>
            @if(!empty($model->effective_date))
                <span class="text-primary">{{ date('d M Y', strtotime($model->effective_date)) }}</span>
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
        <th>Note</th>
        <td>{{ $model->note??'-' }}</td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($model->created_at)) }}</td>
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
