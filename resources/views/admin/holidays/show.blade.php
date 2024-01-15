@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card p-4">
            <div class="card-datatable table-responsive">
                <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <table class="table-striped table salary-table table-bordered">
                        <tr>
                            <th>Name</th>
                            <td>{{ $model->name??'-' }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td><span class="text-info fw-semibold">{{ Str::ucfirst($model->type) }}</span></td>
                        </tr>
                        <tr>
                            <th>Start At</th>
                            <td>{{ date('d, F Y', strtotime($model->start_at)) }}</td>
                        </tr>
                        <tr>
                            <th>End At</th>
                            <td>{{ date('d, F Y', strtotime($model->end_at)) }}</td>
                        </tr>
                        <tr>
                            <th>Total Off Days</th>
                            <td>
                                <span class="badge bg-label-info" text-capitalized="">{{ $model->off_days??0 }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{ getAuthorizeUserName($model->created_by) }}</td>
                        </tr>

                        <tr>
                            <th>Created At</th>
                            <td>{{ date('d F Y', strtotime($model->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{!! $model->description !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        @if(count($model->hasCustomizedEmployees) > 0)
            <div class="card mt-4 p-4">
                <div class="card-datatable table-responsive">
                    <div class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <table class="table-striped table salary-table table-bordered">
                            @if($model->type=="customizable")
                                <tr>
                                    <th colspan="4" class="text-center">Customize Employees</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Shift</th>
                                    <th>R.A</th>
                                </tr>
                                @foreach($model->hasCustomizedEmployees as $customizeEmployee)
                                    @php
                                        $shift = $customizeEmployee->hasEmployee->userWorkingShift;
                                        if (empty($shift)) {
                                            $shift = defaultShift();
                                        } else {
                                            $shift = $shift->workShift;
                                        }

                                        $designation = '-';
                                        if(isset($customizeEmployee->hasEmployee->jobHistory->designation->title) && !empty($customizeEmployee->hasEmployee->jobHistory->designation->title)){
                                            $designation = $customizeEmployee->hasEmployee->jobHistory->designation->title;
                                        }

                                        $manager = '-';
                                        if(isset($customizeEmployee->hasEmployee->departmentBridge->department) && !empty($customizeEmployee->hasEmployee->departmentBridge->department->manager_id)){
                                            $manager = getAuthorizeUserName($customizeEmployee->hasEmployee->departmentBridge->department->manager_id);
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ getAuthorizeUserName($customizeEmployee->employee_id) }}</td>
                                        <td>{{ $designation }}</td>
                                        <td>{{ date('h:i A', strtotime($shift->start_time)) }} - {{ date('h:i A', strtotime($shift->end_time)) }}</td>
                                        <td>{{ $manager }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
@push('js')
@endpush
