<table class="table table-bordered table-striped">
    <tr>
        <th>Employee</th>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasEmployee->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'. $model->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar">
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
        <th>Phone Number</th>
        <td>
            @if(isset($model->hasEmployee->profile) && !empty($model->hasEmployee->profile->phone_number))
                {{ $model->hasEmployee->profile->phone_number }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Working Shift</th>
        <td>
            @if(isset($model->hasEmployee->userWorkingShift->workShift) && !empty($model->hasEmployee->userWorkingShift->workShift->name))
                {{ $model->hasEmployee->userWorkingShift->workShift->name }}
            @else
                @if(isset($model->hasEmployee->departmentBridge->department->departmentWorkShift->workShift) && !empty($model->hasEmployee->departmentBridge->department->departmentWorkShift->workShift->name))
                    {{ $model->hasEmployee->departmentBridge->department->departmentWorkShift->workShift->name }}
                @else
                -
                @endif
            @endif
        </td>
    </tr>
    <tr>
        <th>Date</th>
        <td>
            @if(date('d M, Y', strtotime($model->start_at))==date('d M, Y', strtotime($model->end_at)))
                <b>{{ date('d M, Y', strtotime($model->start_at)) }}</b>
            @else
                <b>{{ date('d M, Y', strtotime($model->start_at)) }}</b> to <b>{{ date('d M, Y', strtotime($model->end_at)) }}</b>
            @endif
        </td>
    </tr>
    <tr>
        <th>Duration</th>
        <td>
            <span class="badge bg-label-info" text-capitalized="">{{ $model->duration??0 }}</span>
        </td>
    </tr>
    <tr>
        <th>Leave Type</th>
        <td>
            @if(isset($model->hasLeaveType) && !empty($model->hasLeaveType->name))
                {{ $model->hasLeaveType->name }}
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
            @if($model->status==1)
                <span class="badge bg-label-success" text-capitalized="">Approved</span>
            @elseif($model->status==2)
                <span class="badge bg-label-danger" text-capitalized="">Rejected</span>
            @else
                <span class="badge bg-label-warning" text-capitalized="">Pending</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Reason</th>
        <td>{{ $model->reason }}</td>
    </tr>
</table>
