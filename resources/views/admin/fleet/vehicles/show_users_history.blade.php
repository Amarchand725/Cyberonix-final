<table class="dt-row-grouping table dataTable dtr-column border-top table-border">
    <thead>
        <tr>
            <th>S.No#</th>
            <th>User</th>
            <th>Deliver Date</th>
            <th>End Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody id="body">
        @php $counter = 1 @endphp
        @foreach ($data['models'] as $key=>$model)
            <tr class="odd" id="id-{{ $model->id }}">
                <td tabindex="0">{{ $counter++ }}.</td>
                <td>
                   <div class="d-flex justify-content-start align-items-center user-name">
                        <div class="avatar-wrapper">
                            <div class="avatar avatar-sm me-3">
                                @if(!empty($model->hasUser->profile->profile))
                                    <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$model->hasUser->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                                @endif
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            @if(!empty($model->hasUser->first_name))
                                <span class="fw-semibold">{{ $model->hasUser->first_name }} {{ $model->hasUser->last_name }}</span>
                                <small class="text-muted">{{ $model->hasUser->email }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if(!empty($model->deliver_date))
                        {{ date('d M Y', strtotime($model->deliver_date)) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!empty($model->end_date))
                        {{ date('d M Y', strtotime($model->end_date)) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if($model->status)
                        <span class="badge bg-label-success" text-capitalized="">Active</span>
                    @else
                        <span class="badge bg-label-danger" text-capitalized="">De-Active</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
