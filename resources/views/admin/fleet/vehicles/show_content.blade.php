<table class="table table-bordered">
    <tr>
        <th>Vehicle</th>
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
        <th>Owner</th>
        <td>
           <div class="d-flex justify-content-start align-items-center user-name">
                <div class="d-flex flex-column">
                    @if(!empty($model->hasOwner->name))
                        <span class="fw-semibold">{{ $model->hasOwner->name }}</span>
                        <small class="text-muted">{{ $model->hasOwner->email }}</small>
                    @else
                    -
                    @endif
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Body Type</th>
        <td>
            @if(!empty($model->hasBodyType))
                {{ $model->hasBodyType->body_type }}
            @else
            -
            @endif
        </td>
        <th>Transmission</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->transmission }}
            </span>
        </td>
    </tr>
    <tr>
        <th>engine capacity</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->engine_capacity }} cc
            </span>
        </td>
        <th>mileage</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->mileage }} KM
            </span>
        </td>
    </tr>
    <tr>
        <th>registration province</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->registration_province }}
            </span>
        </td>
        <th>registration city</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->registration_city }}
            </span>
        </td>
    </tr>
    <tr>
        <th>registration number</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->registration_number }}
            </span>
        </td>
        <th>additional</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ $model->additional }}
            </span>
        </td>
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
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($model->created_at)) }}</td>
    </tr>
    <tr>
        <td colspan="4">
            @if(!empty($model->hasImages))
                @foreach($model->hasImages as $image)
                    <img src="{{ asset('public/upload/vehicle/images') }}/{{ $image->image }}" style="width:150px" />
                @endforeach
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="4">
            @if(!empty($model->video))
                <video width="220" height="140" controls>
                  <source src="{{ asset('public/upload/vehicle/video') }}/{{ $model->video }}" type="video/mp4">
                  <source src="{{ asset('public/upload/vehicle/video') }}/{{ $model->video }}" type="video/ogg">
                  Your browser does not support the video tag.
                </video>
            @else
            -
            @endif
        </td>
    </tr>
</table>
