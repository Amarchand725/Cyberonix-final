<table class="dt-row-grouping table dataTable dtr-column border-top table-border">
    <thead>
        <tr>
            <th>S.No#</th>
            <th>User</th>
            <th style="width:15%">Receive Date</th>
            <th style="width:15%">Deliver Date</th>
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
                                <a href="{{ route('employees.show', $model->hasUser->slug) }}" class="text-body text-truncate">
                                    <span class="fw-semibold">{{ $model->hasUser->first_name }} {{ $model->hasUser->last_name }}</span>
                                </a>
                                <small class="text-muted">{{ $model->hasUser->email }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    @if(!empty($model->receive_date))
                        {{ date('d M, Y', strtotime($model->receive_date)) }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(!empty($model->delivery_date))
                        {{ date('d M, Y', strtotime($model->delivery_date)) }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
