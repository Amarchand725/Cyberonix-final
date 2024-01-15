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
                                    @if (isset($new->user->first_name) ||
                                            (!empty($new->user->first_name) && isset($old->user->first_name) && !empty($old->user->first_name)))
                                        <tr>
                                            <th class="fw-bold">First Name</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user->first_name) ? $old->user->first_name : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user->last_name) ||
                                            (!empty($new->user->last_name) && isset($old->user->last_name) && !empty($old->user->last_name)))
                                        <tr>
                                            <th class="fw-bold">Last Name</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user->last_name) ? $old->user->last_name : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user->email) || (!empty($new->user->email) && isset($old->user->email) && !empty($old->user->email)))
                                        <tr>
                                            <th class="fw-bold">Email</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->user->email) ? ucfirst($old->user->email) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->profile->phone_number) ||
                                            (!empty($new->profile->phone_number) &&
                                                isset($old->profile->phone_number) &&
                                                !empty($old->profile->phone_number)))
                                        <tr>
                                            <th class="fw-bold">Mobile</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->profile->phone_number) ? $old->profile->phone_number : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->profile->gender) ||
                                            (!empty($new->profile->gender) && isset($old->profile->gender) && !empty($old->profile->gender)))
                                        <tr>
                                            <th class="fw-bold">Gender</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->profile->gender) ? ucfirst($old->profile->gender) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->profile->employment_id) ||
                                            (!empty($new->profile->employment_id) &&
                                                isset($old->profile->employment_id) &&
                                                !empty($old->profile->employment_id)))
                                        <tr>
                                            <th class="fw-bold">Employee ID</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->profile->employment_id) ? $old->profile->employment_id : '-' }}
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
                                    @if (isset($new->role->name) || (!empty($new->role->name) && isset($old->role->name) && !empty($old->role->name)))
                                        <tr>
                                            <th class="fw-bold">Role</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->role->name) ? implode(', ', json_decode($old->role->name)) : '-' }}
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
                                    @if (isset($new->salary_history->currency_code) ||
                                            (!empty($new->salary_history->currency_code) &&
                                                isset($old->salary_history->currency_code) &&
                                                !empty($old->salary_history->currency_code)))
                                        <tr>
                                            <th class="fw-bold">Currency</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->salary_history->currency_code) ? $old->salary_history->currency_code : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->salary) ||
                                            (!empty($new->salary_history->salary) &&
                                                isset($old->salary_history->salary) &&
                                                !empty($old->salary_history->salary)))
                                        <tr>
                                            <th class="fw-bold">Salary</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->salary_history->salary) ? $old->salary_history->salary : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->raise_salary) ||
                                            (!empty($new->salary_history->raise_salary) &&
                                                isset($old->salary_history->raise_salary) &&
                                                !empty($old->salary_history->raise_salary)))
                                        <tr>
                                            <th class="fw-bold">Raise Salary</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->salary_history->raise_salary) ? $old->salary_history->raise_salary : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->working_shift_user->start_date) ||
                                            (!empty($new->working_shift_user->start_date) &&
                                                isset($old->working_shift_user->start_date) &&
                                                !empty($old->working_shift_user->start_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'employee_edit'))
                                                    FROM DATE
                                                @else
                                                    JOINING DATE
                                                @endif
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->working_shift_user->start_date) ? date('d M Y', strtotime($old->working_shift_user->start_date)) : '-' }}
                                                to
                                                {{ isset($new->working_shift_user->start_date) ? date('d M Y', strtotime($new->working_shift_user->start_date)) : '-' }}
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
                                    @if (isset($new->job_history->vehicle_name) ||
                                            (!empty($new->job_history->vehicle_name) &&
                                                isset($old->job_history->vehicle_name) &&
                                                !empty($old->job_history->vehicle_name)))
                                        <tr>
                                            <th class="fw-bold">Vehicle Name</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->job_history->vehicle_name) ? $old->job_history->vehicle_name : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->job_history->vehicle_cc) ||
                                            (!empty($new->job_history->vehicle_cc) &&
                                                isset($old->job_history->vehicle_cc) &&
                                                !empty($old->job_history->vehicle_cc)))
                                        <tr>
                                            <th class="fw-bold">Vehicle CC</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->job_history->vehicle_cc) ? $old->job_history->vehicle_cc : '-' }}
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
                        @if (!empty($record) && $record->type != 3)
                            NEW DATA
                        @else
                            DATA
                        @endif
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
                                    @if (isset($new->user->first_name) ||
                                            (!empty($new->user->first_name) && isset($old->user->first_name) && !empty($old->user->first_name)))
                                        <tr>
                                            <th class="fw-bold">First Name</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user->first_name) ? $new->user->first_name : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user->last_name) ||
                                            (!empty($new->user->last_name) && isset($old->user->last_name) && !empty($old->user->last_name)))
                                        <tr>
                                            <th class="fw-bold">Last Name</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user->last_name) ? $new->user->last_name : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->user->email) || (!empty($new->user->email) && isset($old->user->email) && !empty($old->user->email)))
                                        <tr>
                                            <th class="fw-bold">Email</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user->email) ? ucfirst($new->user->email) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->profile->phone_number) ||
                                            (!empty($new->profile->phone_number) &&
                                                isset($old->profile->phone_number) &&
                                                !empty($old->profile->phone_number)))
                                        <tr>
                                            <th class="fw-bold">Mobile</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->profile->phone_number) ? $new->profile->phone_number : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->profile->gender) ||
                                            (!empty($new->profile->gender) && isset($old->profile->gender) && !empty($old->profile->gender)))
                                        <tr>
                                            <th class="fw-bold">Gender</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->profile->gender) ? ucfirst($new->profile->gender) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->profile->employment_id) ||
                                            (!empty($new->profile->employment_id) &&
                                                isset($old->profile->employment_id) &&
                                                !empty($old->profile->employment_id)))
                                        <tr>
                                            <th class="fw-bold">Employee ID</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->profile->employment_id) ? $new->profile->employment_id : '-' }}
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
                                    @if (isset($new->role->name) || (!empty($new->role->name) && isset($old->role->name) && !empty($old->role->name)))
                                        <tr>
                                            <th class="fw-bold">Role</th>
                                            <td class="text-capitalize">
                                                {{ implode(', ', json_decode($new->role->name)) }}
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
                                    
                                    @if (isset($new->working_shift_user->working_shift) ||
                                            (!empty($new->working_shift_user->working_shift) &&
                                                isset($old->working_shift_user->working_shift) &&
                                                !empty($old->working_shift_user->working_shift)))
                                        <tr>
                                            <th class="fw-bold">Work Shift</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->working_shift_user->working_shift) ? $new->working_shift_user->working_shift : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->currency_code) ||
                                            (!empty($new->salary_history->currency_code) &&
                                                isset($old->salary_history->currency_code) &&
                                                !empty($old->salary_history->currency_code)))
                                        <tr>
                                            <th class="fw-bold">Currency</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->salary_history->currency_code) ? $new->salary_history->currency_code : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->salary) ||
                                            (!empty($new->salary_history->salary) &&
                                                isset($old->salary_history->salary) &&
                                                !empty($old->salary_history->salary)))
                                        <tr>
                                            <th class="fw-bold">Salary</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->salary_history->salary) ? $new->salary_history->salary : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->salary_history->raise_salary) ||
                                            (!empty($new->salary_history->raise_salary) &&
                                                isset($old->salary_history->raise_salary) &&
                                                !empty($old->salary_history->raise_salary)))
                                        <tr>
                                            <th class="fw-bold">Raise Salary</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->salary_history->raise_salary) ? $new->salary_history->raise_salary : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->working_shift_user->start_date) ||
                                            (!empty($new->working_shift_user->start_date) &&
                                                isset($old->working_shift_user->start_date) &&
                                                !empty($old->working_shift_user->start_date)))
                                        <tr>
                                            <th class="fw-bold">Joining Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->working_shift_user->start_date) ? date('d M Y', strtotime($new->working_shift_user->start_date)) : '-' }}
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
                                    @if (isset($new->job_history->vehicle_name) ||
                                            (!empty($new->job_history->vehicle_name) &&
                                                isset($old->job_history->vehicle_name) &&
                                                !empty($old->job_history->vehicle_name)))
                                        <tr>
                                            <th class="fw-bold">Vehicle Name</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->job_history->vehicle_name) ? $new->job_history->vehicle_name : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->job_history->vehicle_cc) ||
                                            (!empty($new->job_history->vehicle_cc) &&
                                                isset($old->job_history->vehicle_cc) &&
                                                !empty($old->job_history->vehicle_cc)))
                                        <tr>
                                            <th class="fw-bold">Vehicle CC</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->job_history->vehicle_cc) ? $new->job_history->vehicle_cc : '-' }}
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
