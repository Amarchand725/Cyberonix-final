<table class="table table-bordered table-striped table-responsive">
    <tr>
        <th>Employee</th>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasUser->profile->image))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasUser->profile->image, null) }}" alt="Avatar" class="rounded-circle">
                        @else
                            <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <a href="{{ route('employees.show', $model->hasUser->slug) }}" class="text-body text-truncate">
                        <span class="fw-semibold">{{ Str::ucfirst($model->hasUser->first_name??'') }} {{ Str::ucfirst($model->hasUser->last_name??'') }}</span>
                    </a>
                    <small class="text-muted">{{ $model->hasUser->email??'-' }}</small>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Name as per CNIC</th>
        <td>{{ $model->name_as_per_cnic }}</td>
    </tr>
    <tr>
        <th>CNIC Number</th>
        <td>{{ $model->cnic_number }}</td>
    </tr>
    <tr>
        <th>Date of Birth</th>
        <td>
            @if(!empty($model->date_of_birth))
                {{ date('d M Y', strtotime($model->date_of_birth)) }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Sex</th>
        <td>
            @if($model->sex==1)
                <span class="badge bg-label-success" text-capitalized="">Male</span>
            @else
                <span class="badge bg-label-danger" text-capitalized="">Female</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Marital Status</th>
        <td>
            @if($model->marital_status==1)
                <span class="badge bg-label-success" text-capitalized="">Married</span>
            @else
                <span class="badge bg-label-danger" text-capitalized="">Single</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>
            @if(!empty($model->created_at))
                {{ date('d M Y h:i A', strtotime($model->created_at)) }}
            @else
                -
            @endif
        </td>
    </tr>
    @if(isset($model->hasInsuranceMeta) && !empty($model->hasInsuranceMeta) && sizeof($model->hasInsuranceMeta) > 0)
        <tr>
            <th colspan="2">
                <h5>Family Relations</h5>
            </th>
        </tr>
        <tr>
            <td colspan="2">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Relation</th>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>CNIC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($model->hasInsuranceMeta as $insurance_meta)
                            <tr>
                                <td>{{ Str::ucfirst($insurance_meta->relationship) }}</td>
                                <td>{{ Str::ucfirst($insurance_meta->name) }}</td>
                                <td>{{ date('d M Y', strtotime($insurance_meta->date_of_birth)) }}</td>
                                <td>{{ $insurance_meta->cnic_number??'-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    @endif
</table>
