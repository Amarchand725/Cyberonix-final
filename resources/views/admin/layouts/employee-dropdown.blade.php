@if(isset($employees) && !empty($employees))
    @if($type=='redirect')
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                    <option value="{{ $url }}" selected>Select employee</option>
                    @foreach ($employees as $employee)
                        @if(isset($month) && isset($year))
                            <option value="{{ $url }}/{{ $month.'/'.$year.'/'.$employee->slug }}" {{ $user->slug==$employee->slug?'selected':'' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                            </option>
                        @else
                            <option data-user-slug="{{ $employee->slug }}" value="{{ $url }}/{{ $employee->slug }}" {{ $user->slug==$employee->slug?"selected":"" }}>
                                {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    @elseif($type=='salary-details')
        <select class="select2 form-select form-select-lg" data-allow-clear="true" id="employee-slug" onchange="redirectPage(this)">
            <option value="{{ $url }}" selected>Select employee</option>
            @foreach ($employees as $employee)
                @if(!empty($employee))
                    @php $monthYear = $month.'/'.$year; @endphp
                    @if (!empty($employee->employeeStatus->end_date) && ($monthYear < date('m/Y', strtotime($employee->employeeStatus->end_date))))
                        @php
                            $lastMonth = date('m', strtotime($employee->employeeStatus->end_date));
                            $lastYear = date('Y', strtotime($employee->employeeStatus->end_date));
                        @endphp
                        <option value="{{ $url }}/{{ $month.'/'.$year.'/'.$employee->slug }}" data-user-slug="{{ $employee->slug }}" {{ $user->slug==$employee->slug?"selected":"" }}>
                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                        </option>
                    @else
                    <option value="{{ $url }}/{{ $month.'/'.$year.'/'.$employee->slug }}" data-user-slug="{{ $employee->slug }}" {{ $user->slug==$employee->slug?"selected":"" }}>
                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                        </option>
                    @endif
                @endif
            @endforeach
        </select>
    @elseif($type=='filter')
        <select class="select2 form-select" id="employees_ids" name="employees[]" multiple>
            <option value="All" selected>All Employees</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})</option>
            @endforeach
        </select>
    @elseif($type=='terminated-summary')
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                    <option value="{{ $url }}" selected>Select terminated employee</option>
                    @foreach ($employees as $employee)
                        @php
                            $lastMonth = date('m', strtotime($employee->employeeStatus->end_date));
                            $lastYear = date('Y', strtotime($employee->employeeStatus->end_date));
                        @endphp
                        <option value="{{ $url }}/{{ $lastMonth.'/'.$lastYear.'/'.$employee->slug }}" data-user-slug="{{ $employee->slug }}" {{ $user->id==$employee->id?'selected':'' }}>
                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
@endif
