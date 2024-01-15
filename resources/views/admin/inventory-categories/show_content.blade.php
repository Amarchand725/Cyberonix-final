<table class="table table-bordered table-striped">
    <tr>
        <th>Title</th>
        <td>
            <span class="text-primary fw-semibold">{{ $model->title }}</span>
        </td>
    </tr>
    <tr>
        <th>Departments</th>
        <td>
            @if(isset($model->hasAnnouncementDepartments) && !empty($model->hasAnnouncementDepartments))
                @foreach ($model->hasAnnouncementDepartments as $announcement_department)
                    @if(isset($announcement_department->hasDepartment) && !empty($announcement_department->hasDepartment))
                        <span class="badge bg-label-info mb-1" text-capitalized="">{{ $announcement_department->hasDepartment->name }}</span>
                    @endif
                @endforeach
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Start Date</th>
        <td>
            <span class="text-truncate d-flex align-items-center text-primary">
                {{ date('d M Y', strtotime($model->start_date))??'-' }}
            </span>
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
        <th>Created By</th>
        <td>
            @if($model->createdBy)
                {{ $model->createdBy->first_name }} {{ $model->createdBy->last_name }}
            @else
                -
            @endif
        </td>
    </tr>
    <tr>
        <th>Announcement</th>
        <td>{!! $model->description??'-' !!}</td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($model->created_at)) }}</td>
    </tr>
</table>
