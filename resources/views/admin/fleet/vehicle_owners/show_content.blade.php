<table class="table table-bordered table-striped">
    <tr>
        <th>Owner</th>
        <td>
           <div class="d-flex justify-content-start align-items-center user-name">
                <div class="d-flex flex-column">
                    <span class="fw-semibold">{{ $model->name }} </span>
                    <small class="text-muted">{{ $model->email }}</small>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <th>Company Name</th>
        <td>{{ $model->company_name??'N/A' }}</td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>{{ $model->phone??'N/A' }}</td>
    </tr>
    <tr>
        <th>Address</th>
        <td>{{ $model->address??'N/A' }}</td>
    </tr>
    <tr>
        <th>Created At</th>
        <td>
            @if(!empty($model->created_at))
                <span class="text-primary">{{ date('d M Y', strtotime($model->created_at)) }}</span>
            @else
                -
            @endif
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
    </tr>
</table>
