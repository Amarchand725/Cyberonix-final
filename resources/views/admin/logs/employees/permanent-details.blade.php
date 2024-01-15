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
                                            (!empty($new->user_employment_status->start_date) &&
                                                isset($old->user_employment_status->start_date) &&
                                                !empty($old->user_employment_status->start_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'permanent'))
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
                                            (!empty($new->job_history->joining_date) &&
                                                isset($old->job_history->joining_date) &&
                                                !empty($old->job_history->joining_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'permanent'))
                                                    FROM DATE
                                                @else
                                                    START DATE
                                                @endif
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->job_history->joining_date) ? date('d M Y', strtotime($old->job_history->joining_date)) : '-' }}
                                                to
                                                {{ isset($old->job_history->id) && !empty(getFirstObject("JobHistory", $old->job_history->id)->end_date) ? date('d M Y', strtotime(getFirstObject("JobHistory", $old->job_history->id)->end_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->employee_letter->title) ||
                                            (!empty($new->employee_letter->title) &&
                                                isset($old->employee_letter->title) &&
                                                !empty($old->employee_letter->title)))
                                                <tr>
                                                    <th class="fw-bold">Employment Leter</th>
                                                    <td class="text-capitalize">
                                                        {{ isset($old->employee_letter->title) && !empty($old->employee_letter->title) ? Str::title(str_replace('_', ' ', $old->employee_letter->title)) : '-' }}
                                                    </td>
                                                </tr>
                                    @endif
                                    @if (isset($new->employee_letter->effective_date) ||
                                            (!empty($new->employee_letter->effective_date) &&
                                                isset($old->employee_letter->effective_date) &&
                                                !empty($old->employee_letter->effective_date)))
                                        <tr>
                                            <th class="fw-bold">Effective Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($old->employee_letter->effective_date) ? date('d M Y', strtotime($old->employee_letter->effective_date)) : '-' }}
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
                                    @if (isset($new->user_employment_status->start_date) ||
                                            (!empty($new->user_employment_status->start_date) &&
                                                isset($old->user_employment_status->start_date) &&
                                                !empty($old->user_employment_status->start_date)))
                                        <tr>
                                            <th class="fw-bold">START DATE</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->user_employment_status->start_date) ? date('d M Y', strtotime($new->user_employment_status->start_date)) : '-' }}
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
                                    @if (isset($new->job_history->joining_date) ||
                                            (!empty($new->job_history->joining_date) &&
                                                isset($old->job_history->joining_date) &&
                                                !empty($old->job_history->joining_date)))
                                        <tr>
                                            <th class="fw-bold">Joining Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->job_history->joining_date) ? date('d M Y', strtotime($new->job_history->joining_date)) : '-' }}
                                            </td>
                                        </tr>
                                    @endif
                                    @if (isset($new->employee_letter->title) ||
                                            (!empty($new->employee_letter->title) &&
                                                isset($old->employee_letter->title) &&
                                                !empty($old->employee_letter->title)))
                                                <tr>
                                                    <th class="fw-bold">Employment Leter</th>
                                                    <td class="text-capitalize">
                                                        {{ isset($new->employee_letter->title) && !empty($new->employee_letter->title) ? Str::title(str_replace('_', ' ', $new->employee_letter->title)) : '-' }}
                                                    </td>
                                                </tr>
                                    @endif
                                    @if (isset($new->employee_letter->effective_date) ||
                                            (!empty($new->employee_letter->effective_date) &&
                                                isset($old->employee_letter->effective_date) &&
                                                !empty($old->employee_letter->effective_date)))
                                        <tr>
                                            <th class="fw-bold">Effective Date</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->employee_letter->effective_date) ? date('d M Y', strtotime($new->employee_letter->effective_date)) : '-' }}
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
