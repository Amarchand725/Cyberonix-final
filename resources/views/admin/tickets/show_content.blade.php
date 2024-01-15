<table class="table table-bordered table-striped">
    <tr>
        <th>Employee</th>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar avatar-sm me-3">
                        @if(!empty($model->hasEmployee->profile->profile))
                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                        @else
                        <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
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
        <th>Category</th>
        <td>
            @if(isset($model->hasCategory) && !empty($model->hasCategory->name))
            {{ $model->hasCategory->name }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Reason</th>
        <td>
            @if(isset($model->hasReason) && !empty($model->hasReason->name))
            {{ $model->hasReason->name }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Subject</th>
        <td>
            <span class="fw-semibold">{{ $model->subject }}</span>
        </td>
    </tr>
    <tr>
        <th>Note</th>
        <td>{!! $model->note??'-' !!}</td>
    </tr>
    <tr>
        <th>Attachment</th>
        <td>
            @if(!empty($model->attachment))
            <a href="{{ asset('public/admin/assets/ticket_attachments') }}/{{ $model->attachment }}" download="{{ $model->attachment }}">Download Attachment</a>
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
            <span class="badge bg-label-danger">Pending</span>
            @elseif($model->status==1)
            <span class="badge bg-label-info">Approved By RA</span>
            @elseif($model->status==2)
            <span class="badge bg-label-info">Approved By Admin</span>
            @else
            <span class="badge bg-label-success">Completed</span>
            @endif
        </td>
    </tr>
    <tr>
        <th>Approved By Manager</th>
        <td>
            @if(!empty($model->is_manager_approved))
            {{ date('d M Y h:i A', strtotime($model->is_manager_approved)) }}
            @else
            -
            @endif
        </td>
    </tr>
    @if($model->hasCategory->name == 'IT Equipment')
    <tr>
        <th>Approved By Admin</th>
        <td>
            @if(!empty($model->is_concerned_approved))
            {{ date('d M Y h:i A', strtotime($model->is_concerned_approved)) }}
            @else
            -
            @endif
        </td>
    </tr>
    @endif
</table>
