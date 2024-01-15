<div class="row">
    <!--OLD DATA-->
    <div class="col-md-6  @if (!empty($record) && $record->type == 3) d-none @endif">
        <div class="card accordion-item mb-4">
            <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#oldData" aria-expanded="false" aria-controls="oldData">
                    <h5 class="m-0 fw-bold text-dark">OLD DATA</h5>
                </button>
            </h2>
            <div id="oldData" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="datatable mb-3">
                        <div class="table-responsive table table-striped custom-scrollbar table-view-responsive">
                            <table class="table table-striped custom-table  mb-0 border-top">
                                <tbody>


                                    @if(checkcondition($new->user_employment_status ?? null, $old->user_employment_status ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>User Employment Status</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->user_employment_status->employment_status ?? null, $old->user_employment_status->employment_status ?? null))
                                    <tr>
                                        <th class="fw-bold">Employment Status</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->user_employment_status->employment_status) ? $old->user_employment_status->employment_status : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->user_employment_status->start_date ?? null, $old->user_employment_status->start_date ?? null))
                                    <tr>
                                        <th class="fw-bold">
                                            @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'promotion'))
                                            FROM DATE
                                            @else
                                            START DATE
                                            @endif
                                        </th>
                                        <td class="text-capitalize">
                                            {{ isset($old->user_employment_status->start_date) ? date('d M Y', strtotime($old->user_employment_status->start_date)) : '-' }}
                                            to
                                            {{ isset($old->user_employment_status->id) && isset(getFirstObject("UserEmploymentStatus", $old->user_employment_status->id)->end_date) ? date('d M Y', strtotime(getFirstObject("UserEmploymentStatus", $old->user_employment_status->id)->end_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif

                                    @if(checkcondition($new->job_history ?? null, $old->job_history ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>Job History</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->job_history->designation ?? null, $old->job_history->designation ?? null))
                                    <tr>
                                        <th class="fw-bold">Designation</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->job_history->designation) ? $old->job_history->designation : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->employment_status ?? null, $old->job_history->employment_status ?? null))
                                    <tr>
                                        <th class="fw-bold">Employment Status</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->job_history->employment_status) ? $old->job_history->employment_status : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->joining_date ?? null, $old->job_history->joining_date ?? null))
                                    <tr>
                                        <th class="fw-bold">
                                            @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'promotion'))
                                            FROM DATE
                                            @else
                                            START DATE
                                            @endif
                                        </th>
                                        <td class="text-capitalize">
                                            {{ isset($old->job_history->joining_date) ? date('d M Y', strtotime($old->job_history->joining_date)) : '-' }}
                                            to
                                            {{ isset($old->job_history->id) && isset(getFirstObject("JobHistory", $old->job_history->id)->end_date) ? date('d M Y', strtotime(getFirstObject("JobHistory", $old->job_history->id)->end_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->vehicle_name ?? null, $old->job_history->vehicle_name ?? null))
                                    <tr>
                                        <th class="fw-bold">Vehicle Name</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->job_history->vehicle_name) ? $old->job_history->vehicle_name : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->vehicle_cc ?? null, $old->job_history->vehicle_cc ?? null))
                                    <tr>
                                        <th class="fw-bold">Vehicle CC</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->job_history->vehicle_cc) ? $old->job_history->vehicle_cc : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif


                                    @if(checkcondition($new->salary_history ?? null, $old->salary_history ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>Salary History</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->salary_history->salary ?? null, $old->salary_history->salary ?? null))
                                    <tr>
                                        <th class="fw-bold">Salary</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->salary_history->salary) ? $old->salary_history->salary : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->salary_history->raise_salary ?? null, $old->salary_history->raise_salary ?? null))
                                    <tr>
                                        <th class="fw-bold">Raise Salary</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->salary_history->raise_salary) ? $old->salary_history->raise_salary : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->salary_history->effective_date ?? null, $old->salary_history->effective_date ?? null))
                                    <tr>
                                        <th class="fw-bold">Effective Date</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->salary_history->effective_date) ? date('d M Y', strtotime($old->salary_history->effective_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif

                                    @if(checkcondition($new->user_department ?? null, $old->user_department ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>User Department</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->user_department->department ?? null, $old->user_department->department ?? null))
                                    <tr>
                                        <th class="fw-bold">Department</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->user_department->department) ? $old->user_department->department : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->user_department->start_date ?? null, $old->user_department->start_date ?? null))
                                    <tr>
                                        <th class="fw-bold">
                                            @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'promotion'))
                                            FROM DATE
                                            @else
                                            START DATE
                                            @endif
                                        </th>
                                        <td class="text-capitalize">
                                            {{ isset($old->user_department->start_date) ? date('d M Y', strtotime($old->user_department->start_date)) : '-' }}
                                            to
                                            {{ isset($old->user_department->id) && isset(getFirstObject("DepartmentUser", $old->user_department->id)->end_date) ? date('d M Y', strtotime(getFirstObject("DepartmentUser", $old->user_department->id)->end_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif

                                    @if(checkcondition($new->employee_letter ?? null, $old->employee_letter ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>Employee Letter</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->employee_letter->title ?? null, $old->employee_letter->title ?? null))
                                    <tr>
                                        <th class="fw-bold">Employment Leter</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->employee_letter->title) && !empty($old->employee_letter->title) ? Str::title(str_replace('_', ' ', $old->employee_letter->title)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->employee_letter->effective_date ?? null, $old->employee_letter->effective_date ?? null))
                                    <tr>
                                        <th class="fw-bold">Effective Date</th>
                                        <td class="text-capitalize">
                                            {{ isset($old->employee_letter->effective_date) ? date('d M Y', strtotime($old->employee_letter->effective_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--OLD DATA-->

    <!-- NEWw DATA -->
    <div class=" @if (!empty($record) && $record->type != 3) col-md-6 @else  col-md-12 @endif ">
        <div class="card accordion-item mb-4">
            <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                <button type="button" class="accordion-button show" data-bs-toggle="collapse" data-bs-target="#newData" aria-expanded="false" aria-controls="newData">
                    <h5 class="m-0 fw-bold text-dark">
                        NEW DATA
                    </h5>
                </button>
            </h2>
            <div id="newData" class="accordion-collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="datatable mb-3">
                        <div class="table-show custom-scrollbar table-show-responsive pt-primary">
                            <table class="table custom-table table-responsive table-striped mb-0 border-top">
                                <tbody>
                                    @if(checkcondition($new->user_employment_status ?? null, $old->user_employment_status ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>User Employment</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->user_employment_status->employment_status ?? null, $old->user_employment_status->employment_status ?? null))
                                    <tr>
                                        <th class="fw-bold">Status</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->user_employment_status->employment_status) ? $new->user_employment_status->employment_status : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->user_employment_status->start_date ?? null, $old->user_employment_status->start_date ?? null))
                                    <tr>
                                        <th class="fw-bold">START DATE</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->user_employment_status->start_date) ? date('d M Y', strtotime($new->user_employment_status->start_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(checkcondition($new->job_history ?? null, $old->job_history ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>Job History</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->job_history->designation ?? null, $old->job_history->designation ?? null))
                                    <tr>
                                        <th class="fw-bold">Designation</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->job_history->designation) ? $new->job_history->designation : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->employment_status ?? null, $old->job_history->employment_status ?? null))
                                    <tr>
                                        <th class="fw-bold">Employment Status</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->job_history->employment_status) ? $new->job_history->employment_status : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->joining_date ?? null, $old->job_history->joining_date ?? null))
                                    <tr>
                                        <th class="fw-bold">Joining Date</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->job_history->joining_date) ? date('d M Y', strtotime($new->job_history->joining_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->vehicle_name ?? null, $old->job_history->vehicle_name ?? null))
                                    <tr>
                                        <th class="fw-bold">Vehicle Name</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->job_history->vehicle_name) ? $new->job_history->vehicle_name : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->job_history->vehicle_cc ?? null, $old->job_history->vehicle_cc ?? null))
                                    <tr>
                                        <th class="fw-bold">Vehicle CC</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->job_history->vehicle_cc) ? $new->job_history->vehicle_cc : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(checkcondition($new->salary_history ?? null, $old->salary_history ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>Salary History</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->salary_history->salary ?? null, $old->salary_history->salary ?? null))
                                    <tr>
                                        <th class="fw-bold">Salary</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->salary_history->salary) ? $new->salary_history->salary : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->salary_history->raise_salary ?? null, $old->salary_history->raise_salary ?? null))
                                    <tr>
                                        <th class="fw-bold">Raise Salary</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->salary_history->raise_salary) ? $new->salary_history->raise_salary : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->salary_history->effective_date ?? null, $old->salary_history->effective_date ?? null))
                                    <tr>
                                        <th class="fw-bold">Effective Date</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->salary_history->effective_date) ? date('d M Y', strtotime($new->salary_history->effective_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(checkcondition($new->user_department ?? null, $old->user_department ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>User Department</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->user_department->department ?? null, $old->user_department->department ?? null))
                                    <tr>
                                        <th class="fw-bold">Department</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->user_department->department) ? $new->user_department->department : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->user_department->start_date ?? null, $old->user_department->start_date ?? null))
                                    <tr>
                                        <th class="fw-bold">Effective Date</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->user_department->start_date) ? date('d M Y', strtotime($new->user_department->start_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                    @if(checkcondition($new->employee_letter ?? null, $old->employee_letter ?? null))
                                    <tr>
                                        <td colspan="2">
                                            <h4>Employee Letter</h4>
                                        </td>
                                    </tr>
                                    @if (checkcondition($new->employee_letter->title ?? null, $old->employee_letter->title ?? null))
                                    <tr>
                                        <th class="fw-bold">Employment Leter</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->employee_letter->title) && !empty($new->employee_letter->title) ? Str::title(str_replace('_', ' ', $new->employee_letter->title)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @if (checkcondition($new->employee_letter->effective_date ?? null, $old->employee_letter->effective_date ?? null))
                                    <tr>
                                        <th class="fw-bold">Effective Date</th>
                                        <td class="text-capitalize">
                                            {{ isset($new->employee_letter->effective_date) ? date('d M Y', strtotime($new->employee_letter->effective_date)) : '-' }}
                                        </td>
                                    </tr>
                                    @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- NEWw DATA -->
</div>