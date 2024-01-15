@foreach ($current_month_discrepancies as $current_month_discrepancy)
    <tr
        @if(Auth::user()->hasRole('Department Manager') && $current_month_discrepancy->status==0 && $current_month_discrepancy->is_additional==1)
            data-toggle="tooltip"
            data-placement="top"
            title="Additional Discrepancy"
        @endif
    >
        <td>
            <div>
                <input
                    @if($current_month_discrepancy->status==1)
                        disabled checked class="form-check-input"
                    @elseif(Auth::user()->hasRole('Department Manager') && $current_month_discrepancy->status==0 && $current_month_discrepancy->is_additional==1)
                        disabled class="form-check-input"
                    @else
                        class="form-check-input checkbox"
                    @endif

                    type="checkbox"
                    data-type="{{ $current_month_discrepancy->type }}"
                    value="{{ $current_month_discrepancy->id }}"
                />
            </div>
        </td>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar me-2">
                        @if(isset($current_month_discrepancy->hasEmployee->profile) && !empty($current_month_discrepancy->hasEmployee->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$current_month_discrepancy->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                        @else
                            <img src="{{ asset('public/admin') }}/default.png" alt="Avatar" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="emp_name text-truncate fw-semibold">
                        @if(isset($current_month_discrepancy->hasEmployee) && !empty($current_month_discrepancy->hasEmployee->first_name))
                            {{ $current_month_discrepancy->hasEmployee->first_name }} {{ $current_month_discrepancy->hasEmployee->last_name }}
                        @else
                        -
                        @endif
                    </span>
                    <small class="emp_post text-truncate text-muted">
                        @if(isset($current_month_discrepancy->hasEmployee->jobHistory->designation) && !empty($current_month_discrepancy->hasEmployee->jobHistory->designation->title))
                            {{ $current_month_discrepancy->hasEmployee->jobHistory->designation->title }}
                        @else
                        -
                        @endif
                    </small>
                </div>
            </div>
        </td>
        <td> <span class="fw-semibold text-primary">{{ date('d M, Y h:i A', strtotime($current_month_discrepancy->date)) }}</span></td>
        <td>
            <span class="badge bg-label-primary me-1">
                {{ Str::ucfirst($current_month_discrepancy->type) }}
            </span>
        </td>
        <td>
            {{ date('d M, Y', strtotime($current_month_discrepancy->created_at)) }}
        </td>
        <td>
            @if($current_month_discrepancy->status)
                <span class="badge bg-label-success me-1">Approved</span>
            @elseif($current_month_discrepancy->status==2)
                <span class="badge bg-label-danger me-1">Rejected</span>
            @else
                <span class="badge bg-label-warning me-1">Pending</span>
            @endif
        </td>
    </tr>
@endforeach
