<table class="dt-row-grouping table dataTable dtr-column border-top table-border">
    <thead>
        <tr>
            <th>S.No#</th>
            <th>Vehicle</th>
            <th>Reg. Number</th>
            <th>Rent</th>
            <th>Created At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="body">
        @php $counter = 1 @endphp 
        @foreach ($data['models'] as $key=>$model)
            <tr class="odd" id="id-{{ $model->id }}">
                <td tabindex="0">{{ $counter++ }}.</td>
                <td>
                   <div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                            <div class="avatar avatar-sm me-3">
                                @if(!empty($model->thumbnail))
                                    <img src="{{ asset('public/upload/vehicle/thumbnails') }}/{{ $model->thumbnail }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">{{ $model->name }} ({{ $model->color }})</span>
                            <small class="text-muted">{{ $model->model }} ({{ $model->model_year }})</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="text-truncate d-flex align-items-center text-primary">
                        {{ $model->registration_number }}
                    </span>
                </td>
                <td>
                    @if(!empty($model->hasRent))
                        PKR. {{ number_format($model->hasRent->rent) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!empty($model->created_at))
                        <span class="text-primary">{{ date('d M Y', strtotime($model->created_at)) }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($model->status)
                        <span class="badge bg-label-success" text-capitalized="">Active</span>
                    @else
                        <span class="badge bg-label-danger" text-capitalized="">De-Active</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
