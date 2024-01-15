@extends('admin.layouts.app')
@section('title', $title)

@push('styles')
@endpush

@section('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="card mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card-header">
                                <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-item-center mt-4">
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a href="{{ route('pre_employees.convert-pdf', $model->id) }}" data-toggle="tooltip" data-placement="top" title="Download as PDF" class="btn btn-info">
                                        <span>
                                            <span class="d-none d-sm-inline-block"><i class="fa fa-download"></i> </span>
                                        </span>
                                    </a>
                                </div>
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a href="{{ route('pre_employees.index') }}"
                                        class="btn btn-secondary btn-primary mx-3"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="List of Pre-Employees"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button"
                                        >
                                        <span>
                                            <i class="ti ti-list me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-4 mb-md-2">
                        <div class="accordion accordion-b mt-3" id="accordionExample">
                            <!--Manager-->
                            <div class="card accordion-item mb-4">
                                <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                    <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#managerDetail" aria-expanded="false" aria-controls="managerDetail">
                                        <h5 class="m-0 fw-bold text-dark">MANAGER</h5>
                                    </button>
                                </h2>
                                <div id="managerDetail" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="datatable mb-3">
                                            <div class="table-responsive custom-scrollbar table-view-responsive">
                                                <table class="table table-striped table-responsive custom-table ">
                                                    <thead>
                                                        <tr>
                                                            <th class="fw-bold">Department</th>
                                                            <th class="fw-bold">Name</th>
                                                            <th class="fw-bold">Email</th>
                                                            <th class="fw-bold">Contact</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                @if(isset($model->hasManager->hasManagerDepartment) && !empty($model->hasManager->hasManagerDepartment->name))
                                                                    {{ $model->hasManager->hasManagerDepartment->name }}
                                                                @else
                                                                -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($model->hasManager) && !empty($model->hasManager->first_name))
                                                                {{ $model->hasManager->first_name }} {{ $model->hasManager->last_name }}
                                                                @else
                                                                -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($model->hasManager) && !empty($model->hasManager->email))
                                                                    {{ $model->hasManager->email }}
                                                                @else
                                                                -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if(isset($model->hasManager->profile) && !empty($model->hasManager->profile->phone_number))
                                                                    {{ $model->hasManager->profile->phone_number }}
                                                                @else
                                                                -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <!--Personal Information-->
                                    <div class="card accordion-item mb-4">
                                        <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                            <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#personalDetail" aria-expanded="false" aria-controls="personalDetail">
                                                <h5 class="m-0 fw-bold text-dark">PERSONAL INFORMATION</h5>
                                            </button>
                                        </h2>
                                        <div id="personalDetail" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="datatable mb-3">
                                                    <div class="table-responsive table table-striped custom-scrollbar table-view-responsive">
                                                        <table class="table table-striped custom-table  mb-0 border-top">
                                                            <tbody>
                                                                <tr class="">
                                                                    <th class="fw-bold">Name</th>
                                                                    <td class="text-capitalize">{{ $model->name??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Father Name</th>
                                                                    <td class="text-capitalize">{{ $model->father_name??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Date of birth</th>
                                                                    <td class="text-capitalize">
                                                                        @if(!empty($model->date_of_birth))
                                                                            {{ date('d M Y', strtotime($model->date_of_birth)) }}
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">CNIC</th>
                                                                    <td class="text-capitalize">{{ $model->cnic??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Marital Status</th>
                                                                    <td class="text-capitalize">{{ $model->marital_status??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Hobbies & Intrests</th>
                                                                    <td class="text-capitalize">{{
                                                                        $model->hasResume?$model->hasResume->hobbies_and_interests:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Achievements</th>
                                                                    <td class="text-capitalize">{{
                                                                        $model->hasResume?$model->hasResume->achievements:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Portfolio Link</th>
                                                                    <td class="text-capitalize">
                                                                        {{
                                                                            isset($model->hasResume->portfolio_link)?$model->hasResume->portfolio_link:'N/A'
                                                                        }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Resume</th>
                                                                    <td class="text-capitalize">
                                                                        @if(isset($model->hasResume) && !empty($model->hasResume->resume))
                                                                            <a href="{{ asset('/public/resumes') }}/{{ $model->hasResume->resume }}" download> Download Resume from here </a>
                                                                        @else
                                                                            <span class="text-danger">Not Uploaded Resume</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Contact No</th>
                                                                    <td class="text-capitalize">{{ $model->contact_no??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Emergency Contact No</th>
                                                                    <td class="text-capitalize">{{ $model->emergency_number??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Email</th>
                                                                    <td class="text-capitalize">{{ $model->email??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Address</th>
                                                                    <td class="text-capitalize">{{ $model->address??'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Apartment</th>
                                                                    <td class="text-capitalize">{{ $model->apartment??'N/A' }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Position Information-->
                                </div>
                                <div class="col-md-6">
                                    <div class="card accordion-item mb-4">
                                        <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                            <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#positionDetail" aria-expanded="false" aria-controls="positionDetail">
                                                <h5 class="m-0 fw-bold text-dark">POSITION</h5>
                                            </button>
                                        </h2>
                                        <div id="positionDetail" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="datatable mb-3">
                                                    <div class="table-show custom-scrollbar table-show-responsive pt-primary">
                                                        <table class="table custom-table table-responsive table-striped mb-0 border-top">
                                                            <tbody>
                                                                <tr class="">
                                                                    <th class="fw-bold">Applied Position</th>
                                                                    <td class="text-capitalize">{{ isset($model->hasAppliedPosition->hasPosition)?$model->hasAppliedPosition->hasPosition->title:'N/A' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Expected Salary</th>
                                                                    <td class="text-capitalize">PKR.{{
                                                                        isset($model->hasAppliedPosition)?number_format($model->hasAppliedPosition->expected_salary,2):'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Expected Joining Date</th>
                                                                    <td class="text-capitalize">
                                                                        @if(isset($model->hasAppliedPosition) && !empty($model->hasAppliedPosition->expected_joining_date))
                                                                            {{ date('d M Y', strtotime($model->hasAppliedPosition->expected_joining_date)) }}
                                                                        @else
                                                                        -
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Source of information for this post</th>
                                                                    <td class="text-capitalize">
                                                                        {{ isset($model->hasAppliedPosition)?$model->hasAppliedPosition->source_of_this_post:'N/A' }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--Academic Detail-->
                                    <div class="card accordion-item mb-4">
                                        <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                            <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#academicDetail" aria-expanded="false" aria-controls="academicDetail">
                                                <h5 class="m-0 fw-bold text-dark">ACADEMIC DETAIL</h5>
                                            </button>
                                        </h2>
                                        <div id="academicDetail" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="datatable mb-3">
                                                    <div class="table-responsive custom-scrollbar table-view-responsive">
                                                        <table class="table table-responsive table-striped custom-table mb-0 border-top">
                                                            <tbody>
                                                                <tr class="">
                                                                    <th class="fw-bold">Degree</th>
                                                                    <td class="text-capitalize">{{
                                                                        isset($model->hasAcademic)?$model->hasAcademic->degree:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Major Subject</th>
                                                                    <td class="text-capitalize">{{
                                                                        isset($model->hasAcademic)?$model->hasAcademic->major_subject:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Institute</th>
                                                                    <td class="text-capitalize">{{
                                                                        isset($model->hasAcademic)?$model->hasAcademic->institute:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Year</th>
                                                                    <td class="text-capitalize">{{
                                                                        isset($model->hasAcademic)?$model->hasAcademic->passing_year:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="fw-bold">Grade/GPA</th>
                                                                    <td class="text-capitalize">{{
                                                                        isset($model->hasAcademic)?$model->hasAcademic->grade_or_gpa:'N/A'
                                                                        }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!--Employement History-->
                            <div class="card accordion-item mb-4">
                                <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                    <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#employmentHistory" aria-expanded="false" aria-controls="employmentHistory">
                                        <h5 class="m-0 fw-bold text-dark">EMPLOYEMENT HISTORY</h5>
                                    </button>
                                </h2>
                                <div id="employmentHistory" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="datatable mb-3">
                                            <div class="table-responsive custom-scrollbar table-view-responsive">
                                                <table class="table table-responsive table-striped custom-table mb-0">
                                                    <thead>
                                                        <tr class="">
                                                            <th class="fw-bold">Company</th>
                                                            <th class="fw-bold">Designation</th>
                                                            <th class="fw-bold">Duration</th>
                                                            <th class="fw-bold">Salary</th>
                                                            <th class="fw-bold">Reason of leaving</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($model->haveEmploymentHistories))
                                                        @foreach ($model->haveEmploymentHistories as $history)
                                                        <tr>
                                                            <td class="text-capitalize">{{ $history->company??'N/A' }}</td>
                                                            <td class="text-capitalize">{{ $history->designation??'N/A' }}</td>
                                                            <td class="text-capitalize">{{ $history->duration??'N/A' }}</td>
                                                            <td class="text-capitalize">{{ $history->salary??'N/A' }}</td>
                                                            <td class="text-capitalize">{{ $history->reason_of_leaving??'N/A' }}</td>
                                                        </tr>
                                                        @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="card accordion-item mb-4">
                                <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                                    <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#referenceDetail" aria-expanded="false" aria-controls="referenceDetail">
                                        <h5 class="m-0 fw-bold text-dark">REFERENCE</h5>
                                    </button>
                                </h2>
                                <div id="referenceDetail" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="datatable mb-3">
                                            <div class="table-responsive custom-scrollbar table-view-responsive">
                                                <table class="table table-responsive table-striped custom-table mb-0">
                                                    <thead>
                                                        <tr class="">
                                                            <th class="fw-bold">Ref. Name</th>
                                                            <th class="fw-bold">Company</th>
                                                            <th class="fw-bold">Contact No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @if(!empty($model->haveReferences))
                                                            @foreach ($model->haveReferences as $reference)
                                                                <tr>
                                                                    <td class="text-capitalize">{{ $reference->reference_name??'N/A' }}</td>
                                                                    <td class="text-capitalize">{{ $reference->company??'N/A' }}</td>
                                                                    <td class="text-capitalize">{{ $reference->contact_no??'N/A' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
