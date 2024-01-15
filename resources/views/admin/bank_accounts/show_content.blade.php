<table class="table table-bordered table-striped">
    <tr>
        <th>Employee</th>
        <td><span class="fw-semibold">{{ $model->employee->first_name??'-' }} {{ $model->employee->last_name??'-' }} ( {{ $model->employee->profile->employment_id??'-' }} )</span></td>
    </tr>
    <tr>
        <th>Bank Name</th>
        <td><span class="text-primary">{{ $model->bank_name??'-' }}</span></td>
    </tr>
    <tr>
        <th>Branch Code</th>
        <td>{{ $model->branch_code??'-' }}</td>
    </tr>
    <tr>
        <th>Title</th>
        <td>{{ $model->title??'-' }}</td>
    </tr>
    <tr>
        <th>Account Number</th>
        <td><span class="fw-semibold">{{ $model->account??'-' }}</span></td>
    </tr>
    <tr>
        <th>IBAN</th>
        <td>{!! $model->iban??'-' !!}</td>
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
