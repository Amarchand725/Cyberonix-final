<div class="row">
    <!--OLD DATA-->
    <div class="col-md-6  @if (!empty($record) && $record->type == 3) d-none @endif">
        <div class="card accordion-item mb-4">
            <h2 class="accordion-header py-2 fw-bold" id="headingThree">
                <button type="button" class="accordion-button show" data-bs-toggle="collapse"
                    data-bs-target="#oldData" aria-expanded="false" aria-controls="oldData">
                    <h5 class="m-0 fw-bold text-dark">OLD DATA</h5>
                </button>
            </h2>
            <div id="oldData" class="accordion-collapse show" aria-labelledby="headingThree"
                data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="datatable mb-3">
                        <div
                            class="table-responsive table table-striped custom-scrollbar table-view-responsive">
                            <table class="table table-striped custom-table  mb-0 border-top">
                                <tbody>
                                    @if (isset($new->resignation->employment_status) ||
                                            (!empty($new->resignation->employment_status) &&
                                                isset($old->resignation->employment_status) &&
                                                !empty($old->resignation->employment_status)))
                                        <tr>
                                            <th class="fw-bold">Employment Status</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->employment_status) ? $old->resignation->employment_status : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->subject) ||
                                            (!empty($new->resignation->subject) &&
                                                isset($old->resignation->subject) &&
                                                !empty($old->resignation->subject)))
                                        <tr>
                                            <th class="fw-bold">Subject</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->subject) ? $old->resignation->subject : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->resignation_date) ||
                                            (!empty($new->resignation->resignation_date) &&
                                                isset($old->resignation->resignation_date) &&
                                                !empty($old->resignation->resignation_date)))
                                        <tr>
                                            <th class="fw-bold">Resignation Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->resignation_date) ? date('d M Y', strtotime($old->resignation->resignation_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->reason_for_resignation) ||
                                            (!empty($new->resignation->reason_for_resignation) &&
                                                isset($old->resignation->reason_for_resignation) &&
                                                !empty($old->resignation->reason_for_resignation)))
                                        <tr>
                                            <th class="fw-bold">Reason For Resignation</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->reason_for_resignation) ? $old->resignation->reason_for_resignation : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->notice_period) ||
                                            (!empty($new->resignation->notice_period) &&
                                                isset($old->resignation->notice_period) &&
                                                !empty($old->resignation->notice_period)))
                                        <tr>
                                            <th class="fw-bold">Notice Period</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->notice_period) ? $old->resignation->notice_period : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->comment) ||
                                            (!empty($new->resignation->comment) &&
                                                isset($old->resignation->comment) &&
                                                !empty($old->resignation->comment)))
                                        <tr>
                                            <th class="fw-bold">Comment</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->comment) ? $old->resignation->comment : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->last_working_date) ||
                                            (!empty($new->resignation->last_working_date) &&
                                                isset($old->resignation->last_working_date) &&
                                                !empty($old->resignation->last_working_date)))
                                        <tr>
                                            <th class="fw-bold">Last Working Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->resignation->last_working_date) ? date('d M Y', strtotime($old->resignation->last_working_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif





                                    @if (isset($new->user_employment_status->employment_status) ||
                                            (!empty($new->user_employment_status->employment_status) &&
                                                isset($old->user_employment_status->employment_status) &&
                                                !empty($old->user_employment_status->employment_status)))
                                                <tr>
                                                    <th class="fw-bold">Employment Status</th>
                                                    <td class="text-capitalize">
                                                        {{ isset($old->user_employment_status->employment_status) ? $old->user_employment_status->employment_status : '-' }}
                                                    </td>
                                                </tr>
                                    @endif
                                    @if (isset($new->user_employment_status->start_date) ||
                                            (!empty($new->user_employment_status->start_date) ||
                                                isset($old->user_employment_status->start_date) ||
                                                !empty($old->user_employment_status->start_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                START DATE
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user_employment_status->start_date) ? date('d M Y', strtotime($old->user_employment_status->start_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->job_history->designation) ||
                                            (!empty($new->job_history->designation) &&
                                                isset($old->job_history->designation) &&
                                                !empty($old->job_history->designation)))
                                                <tr>
                                                    <th class="fw-bold">Designation</th>
                                                    <td class="text-capitalize">
                                                        {{ isset($old->job_history->designation) ? $old->job_history->designation : '-' }}
                                                    </td>
                                                </tr>
                                    @endif
                                    @if (isset($new->job_history->employment_status) ||
                                            (!empty($new->job_history->employment_status) &&
                                                isset($old->job_history->employment_status) &&
                                                !empty($old->job_history->employment_status)))
                                                <tr>
                                                    <th class="fw-bold">Employment Status</th>
                                                    <td class="text-capitalize">
                                                        {{ isset($old->job_history->employment_status) ? $old->job_history->employment_status : '-' }}
                                                    </td>
                                                </tr>
                                    @endif
                                    @if (isset($new->job_history->joining_date) ||
                                            (!empty($new->job_history->joining_date) ||
                                                isset($old->job_history->joining_date) ||
                                                !empty($old->job_history->joining_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                START DATE
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->job_history->joining_date) ? date('d M Y', strtotime($old->job_history->joining_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->effective_date) ||
                                            (!empty($new->salary_history->effective_date) ||
                                                isset($old->salary_history->effective_date) ||
                                                !empty($old->salary_history->effective_date)))
                                        <tr>
                                            <th class="fw-bold">Effective Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->salary_history->effective_date) ? date('d M Y', strtotime($old->salary_history->effective_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user_department->department) ||
                                            (!empty($new->user_department->department) &&
                                                isset($old->user_department->department) &&
                                                !empty($old->user_department->department)))
                                        <tr>
                                            <th class="fw-bold">Department</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user_department->department) ? $old->user_department->department : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user_department->start_date) ||
                                            (!empty($new->user_department->start_date) ||
                                                isset($old->user_department->start_date) ||
                                                !empty($old->user_department->start_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                START DATE
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user_department->start_date) ? date('d M Y', strtotime($old->user_department->start_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->working_shift_user->working_shift) ||
                                            (!empty($new->working_shift_user->working_shift) &&
                                                isset($old->working_shift_user->working_shift) &&
                                                !empty($old->working_shift_user->working_shift)))
                                        <tr>
                                            <th class="fw-bold">Work Shift</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->working_shift_user->working_shift) ? $old->working_shift_user->working_shift : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->working_shift_user->start_date) ||
                                            (!empty($new->working_shift_user->start_date) ||
                                                isset($old->working_shift_user->start_date) ||
                                                !empty($old->working_shift_user->start_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                START DATE
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->working_shift_user->start_date) ? date('d M Y', strtotime($old->working_shift_user->start_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user->status) && isset($old->user->status))
                                        <tr>
                                            <th class="fw-bold">Status</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user->status) && $old->user->status == 0 ? 'De-Active' : 'Active' }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($new->user->is_employee) && isset($old->user->is_employee))
                                        <tr>
                                            <th class="fw-bold">Show In List</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user->is_employee) && $old->user->is_employee == 0 ? 'Hide' : 'Show' }}
                                            </td>
                                        </tr>
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
                <button type="button" class="accordion-button show"
                    data-bs-toggle="collapse" data-bs-target="#newData" aria-expanded="false"
                    aria-controls="newData">
                    <h5 class="m-0 fw-bold text-dark">
                        NEW DATA
                    </h5>
                </button>
            </h2>
            <div id="newData" class="accordion-collapse show"
                aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="datatable mb-3">
                        <div
                            class="table-show custom-scrollbar table-show-responsive pt-primary">
                            <table
                                class="table custom-table table-responsive table-striped mb-0 border-top">
                                <tbody>
                                    @if (isset($new->resignation->employment_status) ||
                                            (!empty($new->resignation->employment_status) &&
                                                isset($old->resignation->employment_status) &&
                                                !empty($old->resignation->employment_status)))
                                        <tr>
                                            <th class="fw-bold">Employment Status</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->employment_status) ? $new->resignation->employment_status : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->subject) ||
                                            (!empty($new->resignation->subject) &&
                                                isset($old->resignation->subject) &&
                                                !empty($old->resignation->subject)))
                                        <tr>
                                            <th class="fw-bold">Subject</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->subject) ? $new->resignation->subject : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->resignation_date) ||
                                            (!empty($new->resignation->resignation_date) &&
                                                isset($old->resignation->resignation_date) &&
                                                !empty($old->resignation->resignation_date)))
                                        <tr>
                                            <th class="fw-bold">Resignation Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->resignation_date) ? date('d M Y', strtotime($new->resignation->resignation_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->reason_for_resignation) ||
                                            (!empty($new->resignation->reason_for_resignation) &&
                                                isset($old->resignation->reason_for_resignation) &&
                                                !empty($old->resignation->reason_for_resignation)))
                                        <tr>
                                            <th class="fw-bold">Reason For Resignation</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->reason_for_resignation) ? $new->resignation->reason_for_resignation : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->notice_period) ||
                                            (!empty($new->resignation->notice_period) &&
                                                isset($old->resignation->notice_period) &&
                                                !empty($old->resignation->notice_period)))
                                        <tr>
                                            <th class="fw-bold">Notice Period</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->notice_period) ? $new->resignation->notice_period : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->comment) ||
                                            (!empty($new->resignation->comment) &&
                                                isset($old->resignation->comment) &&
                                                !empty($old->resignation->comment)))
                                        <tr>
                                            <th class="fw-bold">Comment</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->comment) ? $new->resignation->comment : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->resignation->last_working_date) ||
                                            (!empty($new->resignation->last_working_date) &&
                                                isset($old->resignation->last_working_date) &&
                                                !empty($old->resignation->last_working_date)))
                                        <tr>
                                            <th class="fw-bold">Last Working Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->resignation->last_working_date) ? date('d M Y', strtotime($new->resignation->last_working_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user_employment_status->employment_status) ||
                                            (!empty($new->user_employment_status->employment_status) &&
                                                isset($old->user_employment_status->employment_status) &&
                                                !empty($old->user_employment_status->employment_status)))
                                        <tr>
                                            <th class="fw-bold">Employment Status</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user_employment_status->employment_status) ? $new->user_employment_status->employment_status : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user_employment_status->end_date) ||
                                            (!empty($new->user_employment_status->end_date) &&
                                                isset($old->user_employment_status->end_date) &&
                                                !empty($old->user_employment_status->end_date)))
                                        <tr>
                                            <th class="fw-bold">End DATE</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user_employment_status->end_date) ? date('d M Y', strtotime($new->user_employment_status->end_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->job_history->designation) ||
                                            (!empty($new->job_history->designation) &&
                                                isset($old->job_history->designation) &&
                                                !empty($old->job_history->designation)))
                                        <tr>
                                            <th class="fw-bold">Designation</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->job_history->designation) ? $new->job_history->designation : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->job_history->employment_status) ||
                                            (!empty($new->job_history->employment_status) &&
                                                isset($old->job_history->employment_status) &&
                                                !empty($old->job_history->employment_status)))
                                                <tr>
                                                    <th class="fw-bold">Employment Status</th>
                                                    <td class="text-capitalize">
                                                        {{ isset($new->job_history->employment_status) ? $new->job_history->employment_status : '-' }}
                                                    </td>
                                                </tr>
                                    @endif
                                    @if (isset($new->job_history->end_date) ||
                                            (!empty($new->job_history->end_date) &&
                                                isset($old->job_history->end_date) &&
                                                !empty($old->job_history->end_date)))
                                        <tr>
                                            <th class="fw-bold">End Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->job_history->end_date) ? date('d M Y', strtotime($new->job_history->end_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->end_date) ||
                                            (!empty($new->salary_history->end_date) &&
                                                isset($old->salary_history->end_date) &&
                                                !empty($old->salary_history->end_date)))
                                        <tr>
                                            <th class="fw-bold">End Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->salary_history->end_date) ? date('d M Y', strtotime($new->salary_history->end_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user_department->department) ||
                                            (!empty($new->user_department->department) &&
                                                isset($old->user_department->department) &&
                                                !empty($old->user_department->department)))
                                        <tr>
                                            <th class="fw-bold">Department</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user_department->department) ? $new->user_department->department : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user_department->end_date) ||
                                            (!empty($new->user_department->end_date) &&
                                                isset($old->user_department->end_date) &&
                                                !empty($old->user_department->end_date)))
                                        <tr>
                                            <th class="fw-bold">End Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user_department->end_date) ? date('d M Y', strtotime($new->user_department->end_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->working_shift_user->working_shift) ||
                                            (!empty($new->working_shift_user->working_shift) &&
                                                isset($old->working_shift_user->working_shift) &&
                                                !empty($old->working_shift_user->working_shift)))
                                        <tr>
                                            <th class="fw-bold">
                                                Work Shift
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($new->working_shift_user->working_shift) ? $new->working_shift_user->working_shift : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->working_shift_user->end_date) ||
                                            (!empty($new->working_shift_user->end_date) &&
                                                isset($old->working_shift_user->end_date) &&
                                                !empty($old->working_shift_user->end_date)))
                                        <tr>
                                            <th class="fw-bold">End DATE</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->working_shift_user->end_date) ? date('d M Y', strtotime($new->working_shift_user->end_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    
                                    @if (isset($new->user->status) && isset($old->user->status))
                                        <tr>
                                            <th class="fw-bold">Status</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user->status) && $new->user->status == 0 ? 'De-Active' : 'Active' }}
                                            </td>
                                        </tr>
                                    @endif

                                    @if (isset($new->user->is_employee) && isset($old->user->is_employee))
                                        <tr>
                                            <th class="fw-bold">Show From List</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user->is_employee) && $new->user->is_employee == 0 ? 'Hide' : 'Show' }}
                                            </td>
                                        </tr>
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
