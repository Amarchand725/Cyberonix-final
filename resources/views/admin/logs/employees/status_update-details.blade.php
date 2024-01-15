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
                                            <th class="fw-bold">Show In List</th>
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
