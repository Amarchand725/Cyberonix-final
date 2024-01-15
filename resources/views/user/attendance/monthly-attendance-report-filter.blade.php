@php
$total_days = 0;
$regulars = 0;
$late_ins = 0;
$early_outs = 0;
$half_days = 0;
$absents = 0;
@endphp
<div class="row">
    <div class="col-12 ">
        <table class="table  attendance-table">
            <thead>
                <th>#</th>
                <th>User</th>
                <th>Working Days</th>
                <th>Regular</th>
                <th>Late In</th>
                <th>Early Out</th>
                <th>Half Days</th>
                <th>Absents</th>
            </thead>
            <tbody>
            @php $bool = '' @endphp
                @foreach($data['users'] as $key =>  $f_user)
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
                $end = new DateTime($data['to_date']);
                @endphp
                @if(!empty($shift))
                @php
                $statistics = getAttandanceCount($f_user->id, $data['from_date'], $data['to_date'], $data['behavior'], $shift);

                $total_days = $statistics['totalDays'];
                $regulars = $statistics['workDays'];
                if($data['behavior']=='all'){
                $late_ins = $statistics['lateIn'];
                $early_outs = $statistics['earlyOut'];
                $half_days = $statistics['halfDay'];
                $absents = $statistics['absent'];
                }elseif($data['behavior']=='lateIn'){
                $late_ins = $statistics['lateIn'];
                $early_outs = 0;
                $half_days = 0;
                $absents = 0;
                }elseif($data['behavior']=='regular'){
                $late_ins = $statistics['lateIn'];
                $early_outs = $statistics['earlyOut'];
                $half_days = $statistics['halfDay'];
                }elseif($data['behavior']=='earlyout'){
                $early_outs = $statistics['earlyOut'];
                $late_ins = 0;
                $half_days = 0;
                }elseif($data['behavior']=='absent'){
                $absents = $statistics['absent'];
                $late_ins = 0;
                $early_outs = 0;
                $half_days = 0;
                }
                @endphp
                <tr>
                    <td>{{++$key}}</td>
                    <td>{{getUserName($f_user)}}</td>
                    <td>{{$total_days}}</td>
                    <td>{{$regulars}}</td>
                    <td>{{$late_ins}}</td>
                    <td>{{$early_outs}}</td>
                    <td>{{$half_days}}</td>
                    <td>{{$absents}}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>


</div>
