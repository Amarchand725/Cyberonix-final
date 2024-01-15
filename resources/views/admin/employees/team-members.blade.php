@php $counter = 1; @endphp
@foreach ($team_members as $team_member)
    @if(!empty($team_member))
        <tr>
            <td>
                <div class="d-flex justify-content-start align-items-center user-name">
                    <div class="avatar-wrapper">
                        <div class="avatar me-2">
                            @if(isset($team_member->profile) && !empty($team_member->profile->profile))
                                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$team_member->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                            @else
                                <img src="{{ asset('public/admin/assets/img/avatars/default.png') }}" alt="Avatar" class="rounded-circle">
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-column">
                        <span class="emp_name fw-semibold text-truncate">
                            {{ $team_member->first_name }} {{ $team_member->last_name }}
                            (
                                @if(isset($team_member->profile) && !empty($team_member->profile->employment_id))
                                   {{ $team_member->profile->employment_id }}
                                @else
                                    {{ '-' }}
                                @endif
                            )
                        </span>
                        <small class="emp_post text-truncate text-muted">
                            @if(isset($team_member->jobHistory->designation) && !empty($team_member->jobHistory->designation->title))
                                {{ $team_member->jobHistory->designation->title }}
                            @else
                                {{ '-' }}
                            @endif
                        </small>
                    </div>
                </div>
            </td>
            @if(isset($user_id) && !empty($user_id))
                <td>
                    <ul class="list-unstyled mb-0">
                        @foreach ($team_member->getRoleNames() as $role_name)
                            <li class="mb-2">
                                <span class="badge bg-label-primary">
                                    {{ $role_name }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    @if(isset($team_member->departmentBridge->department) && !empty($team_member->departmentBridge->department))
                        <span class="text-primary fw-semibold">{{ $team_member->departmentBridge->department->name }}</span>
                    @else
                        {{ '-' }}
                    @endif
                </td>
                <td>
                    @if(isset($team_member->userWorkingShift->workShift) && !empty($team_member->userWorkingShift->workShift->name))
                        <span class="fw-semibold">{{ $team_member->userWorkingShift->workShift->name }}</span>
                    @else
                        {{ '-' }}
                    @endif
                </td>
            @endif
            <td>
                @if(isset($team_member->employeeStatus->employmentStatus) && !empty($team_member->employeeStatus->employmentStatus->name))
                    @if($team_member->employeeStatus->employmentStatus->name=='Terminated')
                        <span class="badge bg-label-danger me-1">Terminated</span>
                    @elseif($team_member->employeeStatus->employmentStatus->name=='Permanent')
                        <span class="badge bg-label-success me-1">Permanent</span>
                    @elseif($team_member->employeeStatus->employmentStatus->name=='Probation')
                        <span class="badge bg-label-warning me-1">Probation</span>
                    @endif
                @else
                    {{ '-' }}
                @endif
            </td>
        </tr>
    @endif
@endforeach
