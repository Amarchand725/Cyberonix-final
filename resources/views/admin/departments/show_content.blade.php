<table class="table table-bordered table-striped">
    <tr>
        <th>Department</th>
        <td><span class="text-primary fw-semibold">{{ $model->name??'-' }}</span></td>
    </tr>
    <tr>
        <th>Parent Department</th>
        <td>
            @if(isset($model->parentDepartment) && !empty($model->parentDepartment->name))
                <span class="fw-semibold">{{ $model->parentDepartment->name }}</span>
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Manager</th>
        <td>
            @if(isset($model->manager) && !empty($model->manager->first_name))
                {{ $model->manager->first_name }} {{ $model->manager->last_name }}
            @else
            -
            @endif
        </td>
    </tr>
    <tr>
        <th>Description</th>
        <td>{!! $model->description??'-' !!}</td>
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
