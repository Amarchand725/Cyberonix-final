 <table class="dt-row-grouping table dataTable dtr-column border-top table-border table-striped">
    <thead>
        <tr>
            <th scope="col">S.No#</th>
            <th scope="col">Vehicle</th>
            <th scope="col">Rent (PKR)</th>
            <th scope="col">Effected Date</th>
            <th scope="col">End Date</th>
            <th scope="col">Status</th>
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
                <td>PKR. {{ number_format($model->rent) }}</td>
                <td>
                    @if(!empty($model->effective_date))
                        <span class="text-primary">{{ date('d M Y', strtotime($model->effective_date)) }}</span>
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!empty($model->end_date))
                        <span class="text-primary">{{ date('d M Y', strtotime($model->end_date)) }}</span>
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
