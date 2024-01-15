<link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css"/>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md mb-4 mb-md-2">
                    <div class="accordion mt-3" id="accordionExample">
                        <div class="card accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                MANAGER
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="datatable">
                                        <div class="table-responsive custom-scrollbar table-view-responsive">
                                            <table class="table custom-table  mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Department</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Contact</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $model->hasManager->departmentBridge->department->name }} </td>
                                                        <td>{{ $model->hasManager->first_name }} {{ $model->hasManager->last_name }}</td>
                                                        <td>{{ $model->hasManager->email }}</td>
                                                        <td>{{ $model->hasManager->profile->phone_number }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card accordion-item mt-4">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                PERSONAL INFORMATION
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="datatable">
                                        <div class="table-responsive custom-scrollbar table-view-responsive">
                                            <table class="table custom-table  mb-0">
                                                <tbody>
                                                    <tr class="">
                                                        <th>Name</th>
                                                        <td>{{ $model->name??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Father Name</th>
                                                        <td>{{ $model->father_name??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date of birth</th>
                                                        <td>
                                                            @if(!empty($model->date_of_birth))
                                                                {{ date('d, M Y', strtotime($model->date_of_birth)) }}
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>CNIC</th>
                                                        <td>{{ $model->cnic??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Marital Status</th>
                                                        <td>{{ $model->marital_status??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Hobbies & Intrests</th>
                                                        <td>{{
                                                            $model->hasResume?$model->hasResume->hobbies_and_interests:'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Achievements</th>
                                                        <td>{{
                                                            $model->hasResume?$model->hasResume->achievements:'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Portfolio Link</th>
                                                        <td>
                                                            {{
                                                                isset($model->hasResume->portfolio_link)?$model->hasResume->portfolio_link:'N/A'
                                                            }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Contact No</th>
                                                        <td>{{ $model->contact_no??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Emergency Contact No</th>
                                                        <td>{{ $model->emergency_number??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email</th>
                                                        <td>{{ $model->email??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Address</th>
                                                        <td>{{ $model->address??'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Apartment</th>
                                                        <td>{{ $model->apartment??'N/A' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card accordion-item mt-4">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                POSITION
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="datatable">
                                        <div class="table-show custom-scrollbar table-show-responsive pt-primary">
                                            <table class="table custom-table   mb-0">
                                                <tbody>
                                                    <tr class="">
                                                        <th>Applied Position</th>
                                                        <td>{{ isset($model->hasAppliedPosition->hasPosition)?$model->hasAppliedPosition->hasPosition->title:'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Expacted Salary</th>
                                                        <td>PKR.{{
                                                            isset($model->hasAppliedPosition)?number_format($model->hasAppliedPosition->expected_salary,2):'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Expacted Joining Date</th>
                                                        <td>
                                                            @if(isset($model->hasAppliedPosition) && !empty($model->hasAppliedPosition->expected_joining_date))
                                                                {{ date('d, M Y', strtotime($model->hasAppliedPosition->expected_joining_date)) }}
                                                            @else 
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Source of information for this post</th>
                                                        <td>
                                                            {{
                                                                isset($model->hasAppliedPosition)?$model->hasAppliedPosition->source_of_this_post:'N/A'
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card accordion-item mt-4">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                ACADEMIC
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="datatable">
                                        <div class="table-responsive custom-scrollbar table-view-responsive">
                                            <table class="table custom-table   mb-0">
                                                <tbody>
                                                    <tr class="">
                                                        <th>Degree</th>
                                                        <td>{{
                                                            isset($model->hasAcademic)?$model->hasAcademic->degree:'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Major Subject</th>
                                                        <td>{{
                                                            isset($model->hasAcademic)?$model->hasAcademic->major_subject:'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Institute</th>
                                                        <td>{{
                                                            isset($model->hasAcademic)?$model->hasAcademic->institute:'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Year</th>
                                                        <td>{{
                                                            isset($model->hasAcademic)?$model->hasAcademic->passing_year:'N/A'
                                                            }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Grade/GPA</th>
                                                        <td>
                                                            {{
                                                            isset($model->hasAcademic)?$model->hasAcademic->grade_or_gpa:'N/A'
                                                            }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card accordion-item mt-4">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                EMPLOYEEMENT HISTORY
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="datatable">
                                        <div class="table-responsive custom-scrollbar table-view-responsive">
                                            <table class="table custom-table   mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th>Company</th>
                                                        <th>Designation</th>
                                                        <th>Duration</th>
                                                        <th>Salary</th>
                                                        <th>Reason of leaving</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($model->haveEmploymentHistories))
                                                        @foreach ($model->haveEmploymentHistories as $history)
                                                            <tr>
                                                                <td>{{ $history->company??'N/A' }}</td>
                                                                <td>{{ $history->designation??'N/A' }}</td>
                                                                <td>{{ $history->duration??'N/A' }}</td>
                                                                <td>{{ $history->salary??'N/A' }}</td>
                                                                <td>{{ $history->reason_of_leaving??'N/A' }}</td>
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
                        <div class="card accordion-item mt-4">
                            <h2 class="accordion-header" id="headingThree">
                                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                                REFERENCE
                                </button>
                            </h2>
                            <div id="accordionThree" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="datatable">
                                        <div class="table-responsive custom-scrollbar table-view-responsive">
                                            <table class="table custom-table   mb-0">
                                                <thead>
                                                    <tr class="">
                                                        <th>Ref. Name</th>
                                                        <th>Company</th>
                                                        <th>Contact No</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($model->haveReferences))
                                                        @foreach ($model->haveReferences as $reference)
                                                            <tr>
                                                                <td>{{ $reference->reference_name??'N/A' }}</td>
                                                                <td>{{ $reference->company??'N/A' }}</td>
                                                                <td>{{ $reference->contact_no??'N/A' }}</td>
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
