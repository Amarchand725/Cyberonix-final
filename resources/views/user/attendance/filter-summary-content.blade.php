@php
    $total_days = 0;
    $regulars = 0;
    $late_ins = 0;
    $early_outs = 0;
    $half_days = 0;
    $absents = 0;
@endphp
<div class="row">
    <div class="col-12 order-2 table-responsive">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Shift Time</th>
                    <th>Punched In</th>
                    <th>Punched Out</th>
                    <th>Status</th>
                    <th>Working Hours</th>
                </tr>
            </thead>
            <tbody>
            @php $bool = '' @endphp
            @foreach($data['users'] as $f_user)
                @php
                    $bool = true;
                    $shift = '';
                    if(!empty($f_user->userWorkingShift)){
                        $shift = $f_user->userWorkingShift->workShift;
                    }else{
                        if(isset($f_user->departmentBridge->department->departmentWorkShift->workShift) && !empty($f_user->departmentBridge->department->departmentWorkShift->workShift->id)){
                            $shift = $f_user->departmentBridge->department->departmentWorkShift->workShift;
                        }
                    }

                    $begin = new DateTime($data['from_date']);
                    $end   = new DateTime($data['to_date']);
                @endphp
                @if(!empty($shift))
                    @php
                        $statistics = getAttandanceCount($f_user->id, $data['from_date'], $data['to_date'], $data['behavior'], $shift);

                        $total_days = $statistics['totalDays'];
                        $regulars = $regulars+$statistics['workDays'];
                        if($data['behavior']=='all'){
                            $late_ins = $late_ins+$statistics['lateIn'];
                            $early_outs = $early_outs+$statistics['earlyOut'];
                            $half_days = $half_days+$statistics['halfDay'];
                            $absents = $absents+$statistics['absent'];
                        }elseif($data['behavior']=='lateIn'){
                            $late_ins = $late_ins+$statistics['lateIn'];
                            $early_outs = 0;
                            $half_days = 0;
                            $absents = 0;
                        }elseif($data['behavior']=='regular'){
                            $late_ins = $late_ins+$statistics['lateIn'];
                            $early_outs = $early_outs+$statistics['earlyOut'];
                            $half_days = $half_days+$statistics['halfDay'];
                        }elseif($data['behavior']=='earlyout'){
                            $early_outs = $early_outs+$statistics['earlyOut'];
                            $late_ins = 0;
                            $half_days = 0;
                        }elseif($data['behavior']=='absent'){
                            $absents = $absents+$statistics['absent'];
                            $late_ins = 0;
                            $early_outs = 0;
                            $half_days = 0;
                        }
                    @endphp

                    @for($i = $begin; $i <= $end; $i->modify('+1 day'))
                        @php
                            $day=date("D", strtotime($i->format("Y-m-d")));
                            $next=date("Y-m-d", strtotime('+1 day '.$i->format("Y-m-d")));
                            $reponse = getAttandanceSingleRecord($f_user->id,$i->format("Y-m-d"),$next, $data['behavior'] ,$shift);
                        @endphp
                        @if($reponse!=null)
                            @if($bool)
                                <tr class="user_profile_seperator">
                                    <td colspan="6">
                                        <div class="d-flex justify-content-start align-items-start user-name ps-3">
                                            <div class="avatar-wrapper">
                                                <div class="avatar avatar-sm me-2 mt-1">
                                                    @if(!empty($f_user->profile->profile))
                                                        <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$f_user->profile->profile, null) }}" alt="Avatar" class="rounded-circle">
                                                    @else
                                                        <img src="{{ asset('public/admin/default.png') }}" alt="Avatar" class="rounded-circle">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('employees.show', $f_user->slug) }}" class="text-body text-truncate text-start">
                                                    <span class="fw-semibold">{{ $f_user->first_name??'' }} {{ $f_user->last_name??'' }}</span>
                                                </a>
                                                <small class="text-muted">{{ $f_user->email??'-' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php $bool = false; @endphp
                            @endif
                            @if($day!='Sat' && $day!='Sun')
                                <tr class="{{$day}}">
                                    <td>{{$i->format("d-m-Y")}}</td>
                                    <td>{{$reponse['shiftTiming']}}</td>
                                    <td>@if($day!='Sat' && $day!='Sun'){{$reponse['punchIn']}}@else{{'-'}}@endif</td>
                                    <td>@if($day!='Sat' && $day!='Sun'){{$reponse['punchOut']}}@else{{'-'}}@endif</td>
                                    <td>@if($day!='Sat' && $day!='Sun'){!! $reponse['label'] !!}@else{{'-'}}@endif</td>
                                    <td>@if($day!='Sat' && $day!='Sun'){{$reponse['workingHours']}}@else{{'-'}}@endif</td>
                                </tr>
                            @endif
                        @endif
                    @endfor
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="col-12 order-1 mb-3">
        <div class="card mb-4">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                  <h4 class="mb-2">{{$total_days}}</h4>
                                  <p class="mb-0 fw-medium">Working Days</p>
                                </div>
                                <span class="avatar p-2 me-lg-4">
                                  <span class="avatar-initial bg-label-secondary rounded"><i class="ti-md ti ti-calendar-stats text-primary"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                    <h4 class="mb-2">{{$regulars}}</h4>
                                    <p class="mb-0 fw-medium">Regular</p>
                                </div>
                                <span class="avatar p-2 me-lg-4">
                                  <span class="avatar-initial bg-label-secondary rounded"><i class="ti-md ti ti-square-check text-primary"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                  <h4 class="mb-2">{{$late_ins}}</h4>
                                  <p class="mb-0 fw-medium">Late In</p>
                                </div>
                                <span class="avatar p-2 me-lg-4">
                                  <span class="avatar-initial bg-label-secondary rounded"><i class="ti-md ti ti-arrow-bar-to-down text-primary"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                  <h4 class="mb-2">{{$early_outs}}</h4>
                                  <p class="mb-0 fw-medium">Early Out</p>
                                </div>
                                <span class="avatar p-2 me-lg-4">
                                  <span class="avatar-initial bg-label-secondary rounded"><i class="ti-md ti ti-arrow-bar-to-up text-primary"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                  <h4 class="mb-2">{{$half_days}}</h4>
                                  <p class="mb-0 fw-medium">Half Day</p>
                                </div>
                                <span class="avatar p-2 me-lg-4">
                                  <span class="avatar-initial bg-label-secondary rounded"><i class="ti-md ti ti-square-half text-primary"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                        <div class="col-sm-2 col-lg-2">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-3 pb-sm-0">
                                <div>
                                  <h4 class="mb-2">{{$absents}}</h4>
                                  <p class="mb-0 fw-medium">Absents</p>
                                </div>
                                <span class="avatar p-2 me-lg-4">
                                  <span class="avatar-initial bg-label-secondary rounded"><i class="ti-md ti ti-clock-off text-primary"></i></span>
                                </span>
                            </div>
                            <hr class="d-none d-sm-block d-lg-none me-4">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
