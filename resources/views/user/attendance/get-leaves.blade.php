@foreach ($current_month_leaves as $current_month_leave)
    <tr>
        <td>
            <div>
                <input 
                    @if($current_month_leave->status==1) 
                        disabled checked class="form-check-input"
                    @else
                        class="form-check-input checkbox" 
                    @endif 
                    type="checkbox" 
                    data-type="{{ $current_month_leave->behavior_type }}"
                    value="{{ $current_month_leave->id }}" 
                />
            </div>
        </td>
        <td>
            <div class="d-flex justify-content-start align-items-center user-name">
                <div class="avatar-wrapper">
                    <div class="avatar me-2">
                        @if(isset($current_month_leave->hasEmployee->profile) && !empty($current_month_leave->hasEmployee->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$current_month_leave->hasEmployee->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                        @else
                            <img src="{{ asset('public/admin') }}/default.png" alt="Avatar" class="rounded-circle">
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <span class="emp_name text-truncate">
                        @if(isset($current_month_leave->hasEmployee) && !empty($current_month_leave->hasEmployee->first_name))
                            {{ $current_month_leave->hasEmployee->first_name }} {{ $current_month_leave->hasEmployee->last_name }}
                        @else
                        -
                        @endif
                    </span>
                    <small class="emp_post text-truncate text-muted">
                        @if(isset($current_month_leave->hasEmployee->jobHistory->designation) && !empty($current_month_leave->hasEmployee->jobHistory->designation->title))
                            {{ $current_month_leave->hasEmployee->jobHistory->designation->title }}
                        @else
                        -
                        @endif
                    </small>
                </div>
            </div>
        </td>
        <td>{{ date('d M, Y', strtotime($current_month_leave->start_at)) }}</td>
        <td>
            @if($current_month_leave->behavior_type == 'absent')
                <span class="badge bg-label-primary me-1">Absent</span>
            @elseif($current_month_leave->behavior_type == 'lasthalf')
                <span class="badge bg-label-warning me-1">Last Half</span>
            @elseif($current_month_leave->behavior_type == 'firsthalf')
                <span class="badge bg-label-info me-1">First Half</span>
            @else
                <span class="badge bg-label-primary me-1">{{ $current_month_leave->behavior_type }}</span>
            @endif
        </td>
        <td><span class="badge bg-label-danger me-1"> {{ $current_month_leave->duration }}</span></td>
        <td>
            {{ date('d M Y', strtotime($current_month_leave->created_at)) }}
        </td>
        
        <td>
            @if($current_month_leave->status)
                <span class="badge bg-label-success me-1">Approved</span>
            @elseif($current_month_leave->status==2)
                <span class="badge bg-label-danger me-1">Rejected</span>
            @else
                <span class="badge bg-label-warning me-1">Pending</span>
            @endif
        </td>
    </tr>
@endforeach
