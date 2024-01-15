<table class="table table-bordered table-striped">
    <tr>
        <th>User</th>
        <td>
            @if(isset($data->profile) && !empty($data->profile))
                <img src="{{ resize(asset('public/admin/assets/img/avatars') .'/'.$data->profile, null) }}" alt="Avatar" class="rounded-circle img-avatar me-1" style="width:40px; height:40px">
            @else
                <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle img-avatar" style="width:40px; height:40px">
            @endif

            <span class="fw-semibold text-primary">{{ Str::ucfirst($data->name) }}</span>
        </td>
    </tr>
    <tr>
        <th>Title</th>
        <td><span class="fw-semibold">{!! $data->title !!}</span></td>
    </tr>
    @if(isset($data->type))
        <tr>
            <th>Type</th>
            <td>{!! $data->type !!}</td>
        </tr>
    @endif
    @if(isset($data->reason))
        <tr>
            <th>Reason</th>
            <td>
                {!! $data->reason !!}
            </td>
        </tr>
    @else
        <tr>
            <th>Description</th>
            <td>
                {!! $data->description !!}
            </td>
        </tr>
    @endif
    <tr>
        <th>Created At</th>
        <td>{{ date('d F Y', strtotime($model->created_at)) }}</td>
    </tr>
</table>
