<table class="table table-bordered table-striped">
    <tr>
        <th>Employee</th>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasEmployee->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar">
                        @else
                            <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <a href="{{ route('employees.show', $model->hasEmployee->slug) }}" class="text-body text-truncate">
                        <span class="fw-semibold">{{ Str::ucfirst($model->hasEmployee->first_name??'') }} {{ Str::ucfirst($model->hasEmployee->last_name??'') }}</span>
                    </a>
                    <small class="text-muted">{{ $model->hasEmployee->email??'-' }}</small>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Employment Status</th>
        <td>
            @if(isset($model->hasEmploymentStatus) && !empty($model->hasEmploymentStatus))
                <span class="badge bg-label-{{ $model->hasEmploymentStatus->class }}">{{ $model->hasEmploymentStatus->name }}</span>
            @else
            -
            @endif
        </td>
    </tr>
    @if($model->is_rehired)
        <tr>
            <th>Re-Hired</th>
            <td>
                <span class="badge bg-label-{{ $model->hasEmploymentStatus->class }}">{{ date('d F Y h:i A', strtotime($model->updated_at)) }}</span>
            </td>
        </tr>
    @endif
    <tr>
        <th>Resignation Date</th>
        <td>
            @if(!empty($model->resignation_date))
                {{ date('d, M Y', strtotime($model->resignation_date)) }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Notice Period</th>
        <td>
            {{ $model->notice_period }}
        </td>
    </tr>
    <tr>
        <th>Last Working Date</th>
        <td>
            @if(!empty($model->last_working_date))
                {{ date('d F Y', strtotime($model->last_working_date)) }}
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
        <th>Status</th>
        <td>
            @if($model->status==0)
                <span class="badge bg-label-warning">Pending</span>
            @elseif($model->status==1)
                <span class="badge bg-label-info">Approved By RA</span>
            @elseif($model->status==2)
                <span class="badge bg-label-primary">Approved By Admin</span>
            @else
                <span class="badge bg-label-danger">Rejected</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Subject</th>
        <td>
            @if(!empty($model->subject))
                {!! $model->subject !!}
            @else
                N/A
            @endif
        </td>
    </tr>
    <tr>
        <th>Reason for resignation</th>
        <td>
            {!! $model->reason_for_resignation !!}
        </td>
    </tr>
    <tr>
        <th>Comment</th>
        <td>
            {!! $model->comment !!}
        </td>
    </tr>
</table>
