<table class="table table-bordered table-striped">
    <tr>
        <th>Stationary Category</th>
        <td><span class="text-primary">{{ $model->stationartCategory->stationary_category??'-' }}</span></td>
    </tr>
    <tr>
        <th>Stationary Quantity</th>
        <td><span class="text-primary">{{ $model->quantity??'-' }}</span></td>
    </tr>
    <tr>
        <th>Stationary Price</th>
        <td><span class="text-primary">${{ number_format($model->price, 2)??'-' }}</span></td>
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
