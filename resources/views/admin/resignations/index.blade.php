@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())
@section('content')
@if(!isset($temp))
<input type="hidden" id="page_url" value="{{ route('resignations.index') }}">
@else
<input type="hidden" id="page_url" value="{{ route('resignations.trashed') }}">
@endif

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-3">
                        @if(!isset($temp))
                        <div class="dt-buttons flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('resignations.trashed') }}" class="btn btn-label-danger me-3">
                                <span>
                                    <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                </span>
                            </a>
                        </div>
                        @can('resignations-create')
                        <div class="dt-buttons btn-group flex-wrap">
                            <button data-toggle="tooltip" data-placement="top" title="Add Resignation" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('resignations.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-sm"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </button>
                        </div>
                        @endcan
                        @else
                        <div class="dt-buttons btn-group flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('resignations.index') }}" class="btn btn-success btn-primary mx-3">
                                <span>
                                    <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">View All Records</span>
                                </span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="col-md-8">
                    <span class="card-title mb-0">
                        <div class="d-flex align-items-center">
                            @if(isset($user->profile) && !empty($user->profile->profile))
                            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$user->profile->profile, null) }}" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                            @else
                            <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                            @endif
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="mx-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1 text-capitalize">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                    </div>
                                    <small class="text-muted">
                                        @if(isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                        {{ $user->jobHistory->designation->title }}
                                        @else
                                        -
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <hr />

            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class=" dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                            <thead>
                                <tr>
                                    <th scope="col">S.No#</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Employment Status</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Resignation Date</th>
                                    <th scope="col">Notice Period</th>
                                    <th scope="col">Last Date</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add resignation Modal -->
<div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-top modal-simple">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="post" data-modal-id="offcanvasAddAnnouncement" id="create-form" enctype="multipart/form-data">
                    @csrf

                    @if(isset($data['employment_status']))
                        <input type="hidden" name="employment_status" id="employment_status" value="{{ $data['employment_status']->id }}" />
                        <input type="hidden" name="type" id="my-resign" value="my-resign" />
                    @endif
                    <span id="edit-content">
                        <div class="row">
                            <div class="col-12">
                                <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="subject_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <label class="form-label" for="resignation_date">Resignation Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="resignation_date" name="resignation_date">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="resignation_date_error" class="text-danger error"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mt-3">
                            <label class="form-label" for="reason_for_resignation">Reason for resignation </label>
                            <textarea class="form-control" rows="5" name="reason_for_resignation" id="reason_for_resignation" placeholder="Enter reason for resignation here">{{ old('reason_for_resignation') }}</textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="reason_for_resignation_error" class="text-danger error"></span>
                        </div>
                    </span>

                    <div class="col-12 mt-3 action-btn">
                        <div class="demo-inline-spacing sub-btn">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                        <div class="demo-inline-spacing loading-btn" style="display: none;">
                            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add resignation Modal -->

<!-- Add re-hire Modal -->
<div class="modal fade" id="create-re-hire-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2" id="modal-label"></h3>
                </div>
                <!-- Add role form -->
                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="create-re-hire-form-modal" id="create-form">
                    @csrf

                    <span id="edit-content"></span>

                    <div class="col-12 mt-3 action-btn">
                        <div class="demo-inline-spacing sub-btn">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                        <div class="demo-inline-spacing loading-btn" style="display: none;">
                            <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="reset" class="btn btn-label-secondary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>
        </div>
    </div>
</div>
<!-- Add re-hire Modal -->

<div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>

                <div class="col-12">
                    <span id="show-content"></span>
                </div>

                <div class="col-12 mt-3 text-end">
                    <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    //datatable
    var table = $('.data_table').DataTable();
    if ($.fn.DataTable.isDataTable('.data_table')) {
        table.destroy();
    }
    $(document).ready(function() {
        var page_url = $('#page_url').val();
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: page_url + "?loaddata=yes",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'employee_id',
                    name: 'employee_id'
                },
                {
                    data: 'employment_status_id',
                    name: 'employment_status_id'
                },
                {
                    data: 'subject',
                    name: 'subject'
                },
                {
                    data: 'resignation_date',
                    name: 'resignation_date'
                },
                {
                    data: 'notice_period',
                    name: 'notice_period'
                },
                {
                    data: 'last_working_date',
                    name: 'last_working_date'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
        $('.data_table').parent().addClass('table-responsive');
    });
</script>


<script>
    // for employee create and edit modal  currency rate on change
    $(document).on("change", "#currency", function() {
        var code = $(this).val();
        var salary = $("#salary").val();
        if (code && code == "USD") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }

    });
    $(document).on("keyup", "#salary", function() {
        var code = $("#currency").val();
        var salary = $(this).val();
        if (code && code == "USD") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }
    });
    // for employee create and edit modal  currency rate on change



    // for promotion modal  currency rate on change
    $(document).on("change", "#currency_promotion", function() {
        var code = $(this).val();
        var salary = $("#raise_salary").val();
        if (code && code == "USD") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }

    });
    $(document).on("keyup", "#raise_salary", function() {
        var code = $("#currency_promotion").val();
        var salary = $(this).val();
        if (code && code == "USD") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }
    });

    // for promotion modal



    function convertCurrency(code, salary) {
        if (code && salary) {
            $.ajax({
                url: "{{ route('employees.getCurrencyRate') }}",
                method: "GET",
                data: {
                    code: code,
                    salary: salary,
                },
                beforeSend: function() {
                    $(".currency_rate_after_conversion").html('<div class="spinner-grow" style="width: 12px;height: 12px !important;color: #00cfe8;" role="status"><span class="sr-only">Loading...</span></div>')
                },
                success: function(res) {
                    if (res.success == false) {
                        $(".currency_rate_after_conversion").html("");
                    }
                    if (res.success == true) {

                        $(".conversion_amount_hidden").val(res.data.convertedAmount);
                        $(".conversion_rate").val(res.data.conversionRate)
                        $(".currency_rate_after_conversion").html("After Conversion: " + res.data.convertedAmountWithSymbol)
                    }
                },
                error: function(xhr, status, error) {
                    $(".currency_rate_after_conversion").html("");
                    console.log(error)
                    console.log(xhr)
                }
            });
        }
    }
</script>
@endpush
