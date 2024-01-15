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
        <th>Role</th>
        <td>
            <span class="badge bg-label-primary">{{ $model->hasEmployee->getRoleNames()->first() }}</span>
        </td>
    </tr>
    <tr>
        <th>Department</th>
        <td>
            @if(isset($model->hasEmployee->departmentBridge->department) && !empty($model->hasEmployee->departmentBridge->department->name))
                <span class="text-primary">{{ $model->hasEmployee->departmentBridge->department->name }}</span>
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Shift</th>
        <td>
            @if(isset($model->hasEmployee->userWorkingShift->workShift) && !empty($model->hasEmployee->userWorkingShift->workShift->name))
                {{ $model->hasEmployee->userWorkingShift->workShift->name }}
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <th>Note</th>
        <td>
            {{ $model->note??'-' }}
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
                <span class="badge bg-label-danger">Pending</span>
            @else
                <span class="badge bg-label-success">Active</span>
            @endif
        </td>
    </tr>
</table>
