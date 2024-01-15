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
                                            (!empty($new->working_shift_user->start_date) &&
                                                isset($old->working_shift_user->start_date) &&
                                                !empty($old->working_shift_user->start_date)))
                                        <tr>
                                            <th class="fw-bold">
                                                @if (isset($record->modelEvent->slug) && !empty($record->modelEvent->slug == 'shift_add'))
                                                    FROM DATE
                                                @else
                                                    START DATE
                                                @endif
                                            </th>
                                            <td class="text-capitalize">
                                                {{ isset($old->working_shift_user->start_date) ? date('d M Y', strtotime($old->working_shift_user->start_date)) : '-' }}
                                                to
                                                {{ isset($old->working_shift_user->id) ? date('d M Y', strtotime(getFirstObject("WorkingShiftUser", $old->working_shift_user->id)->end_date)) : '-' }}
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
                                    @if (isset($new->working_shift_user->start_date) ||
                                            (!empty($new->working_shift_user->start_date) &&
                                                isset($old->working_shift_user->start_date) &&
                                                !empty($old->working_shift_user->start_date)))
                                        <tr>
                                            <th class="fw-bold">START DATE</th>
                                            <td class="text-capitalize">
                                                {{ isset($new->working_shift_user->start_date) ? date('d M Y', strtotime($new->working_shift_user->start_date)) : '-' }}
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
