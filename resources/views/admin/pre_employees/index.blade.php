@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
@endpush

@section('content')
@if(isset($temp))
<input type="hidden" id="page_url" value="{{ route('pre_employees.index') }}">
@else
<input type="hidden" id="page_url" value="{{ route('pre_employees.trashed') }}">
@endif

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                @can('pre_employees-delete')
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        @if(isset($temp))
                        <div class="dt-buttons flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('pre_employees.trashed') }}" class="btn btn-label-danger mx-4">
                                <span>
                                    <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                </span>
                            </a>
                        </div>
                        <div class="dt-buttons btn-group flex-wrap">
                            <button data-toggle="tooltip" data-placement="top" title="Refresh " type="button" class="btn btn-secondary add-new btn-primary me-3" id="refresh-btn">
                                <span>
                                    <i class="ti ti-refresh ti-sm"></i>
                                    <span class="d-none d-sm-inline-block">Refresh Records</span>
                                </span>
                            </button>
                        </div>


                        {{-- <div class="dt-buttons btn-group flex-wrap">
                                <button data-toggle="tooltip" data-placement="top" title="Add Pre Employee" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('pre_employees.storePreEmployees') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
                        <span>
                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                            <span class="d-none d-sm-inline-block">Add New</span>
                        </span>
                        </button>
                    </div> --}}

                    @else
                    <div class="dt-buttons btn-group flex-wrap">
                        <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('pre_employees.index') }}" class="btn btn-success btn-primary mx-3">
                            <span>
                                <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">View All Records</span>
                            </span>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endcan
        </div>
    </div>

    <br />
    <!-- Users List Table -->
    <div class="card">
        <div class="card-datatable">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="container">
                    <table class="datatables-users table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                        <thead>
                            <tr>
                                <th>S.No#</th>
                                <th>Applicant</th>
                                <th>Applied Position</th>
                                <th>Expected Salary</th>
                                <th>Manager</th>
                                <th>Applied At</th>
                                <th>Status</th>
                                <th>Is Exist</th>
                                <th>Actions</th>
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

<div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-top modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2" id="modal-label"></h3>
                </div>
                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="create-form-modal" id="create-form">
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
            </div>
        </div>
    </div>
</div>
<div class="appendUpdateManagerModal"></div>
<!-- Add Employee Modal -->
<!-- <div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-xl modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                    @csrf

                    <span id="edit-content">
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Manager</label>
                                <select name="manager_id" id="manager_id" class="form-control select2">
                                    <option value=""> Select </option>
                                    @if(isset($managers) && count($managers) > 0)
                                    @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}">{{ getUserName($manager) ?? null }}</option>
                                    @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="manager_id_error" class="text-danger error"></span>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Name">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="name_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Father Name</label>
                                <input type="text" name="father_name" id="father_name" class="form-control" value="{{ old('father_name') }}" placeholder="Father Name">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="father_name_error" class="text-danger error"></span>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Email">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="email_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}" placeholder="Date of Birth">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="date_of_birth_error" class="text-danger error"></span>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">CNIC</label>
                                <input type="text" name="cnic" id="cnic" class="maskValidate cnicValidate form-control" data-inputmask="'mask': '99999-9999999-9'" placeholder="CNIC" value="{{ old('cnic') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="cnic_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Contact Number</label>
                                <input type="text" name="contact_number" id="contact_number" placeholder="Contact Number" class="maskValidate contactValidate  form-control" value="{{ old('contact_number') }}" data-inputmask="'mask': '0399-9999999'" maxlength="12">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="contact_number_error" class="text-danger error"></span>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Emergency Contact Number</label>
                                <input type="text" name="emergency_number" id="emergency_number" placeholder="Emergency Contact Number" class="maskValidate contactValidate  form-control" value="{{ old('emergency_number') }}" data-inputmask="'mask': '0399-9999999'" maxlength="12">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="emergency_number_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Address</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Address" value="{{ old('address') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="address_error" class="text-danger error"></span>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Apartment</label>
                                <input type="text" name="apartment" id="apartment" class="form-control" placeholder="Apartment" value="{{ old('apartment') }}">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="apartment_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Marital Status</label>
                                <select name="marital_status" id="marital_status" class="form-control">
                                    <option value="single" @if(!empty(old('marital_status')) && old('marital_status')=='single' ) selected @endif>Single</option>
                                    <option value="married" @if(!empty(old('marital_status')) && old('marital_status')=='married' ) selected @endif>Married</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="marital_status_error" class="text-danger error"></span>
                            </div>
                            <div class="col-6 col-md-6 mb-3">
                                <label class="form-label" for="title">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="0" {{ !empty(old('status')) && old('status') == 0 ? 'selected' : '' }}>Pendding</option>
                                    <option value="1" {{ !empty(old('status')) && old('status') == 1 ? 'selected' : '' }}>Approved</option>
                                    <option value="2" {{ !empty(old('status')) && old('status') == 2 ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="status_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 mb-3">
                            <label class="form-label" for="title">Note</label>
                            <textarea cols="30" rows="3" name="note" id="note" class="form-control" placeholder="Note">{{ old('note') }}</textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="note_error" class="text-danger error"></span>
                        </div>
                    </span>
                    <div class="col-12 mt-3 action-btn">
                        <div class="demo-inline-spacing sub-btn text-end">
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
</div> -->
<!--/ Add Employee Modal -->

@endsection
@push('js')
<!-- mask -->
<script src="{{ asset('public/pre-employees/js/input-mask.js') }}"></script>

<script>
    // var table = $('.data_table').DataTable();
    // if ($.fn.DataTable.isDataTable('.data_table')) {
    //     table.destroy();
    // }
    var page_url = $('#page_url').val();
    var table = $('.data_table').DataTable({
        "paging": true,
        "processing": true,
        "serverSide": true,
        "ajax": page_url + "?loaddata=yes",
        "searching": true,
        "smart": true,
        "pageLength": 10,
        "pagingType": "full_numbers",
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'applied_position',
                name: 'applied_position'
            },
            {
                data: 'expected_salary',
                name: 'expected_salary'
            },
            {
                data: 'manager_id',
                name: 'manager_id'
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
                data: 'is_exist',
                name: 'is_exist'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ]
    });
    $(document).on("click", "#refresh-btn", function() {
        // loadPageData()
        // var table = $('.data_table').DataTable();
        table.ajax.reload(null, false)
    });


    $(document).on('change', '.is_vehicle', function() {
        var is_vehicle = $(this).val();
        if (is_vehicle == 1) {
            var html = '<div class="col-12 col-md-12 mt-2">' +
                '<label class="form-label" for="vehicle_cc">Vehicle Engine Capacity (CC) <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" id="vehicle_cc" name="vehicle_cc" placeholder="Enter vehicle engine cc">' +
                '<div class="fv-plugins-message-container invalid-feedback"></div>' +
                '<span id="vehicle_cc_error" class="text-danger error"></span>' +
                '</div>';
            $('.vehicle-content').html(html);
        } else {
            $('.vehicle-content').html("");
        }
    });
    $(document).on("click", ".getManagers", function() {
        $(".appendUpdateManagerModal").empty();
        var employee_id = $(this).data('employee-id');
        var manager_id = $(this).data('manager-id');
        $.ajax({
            method: "GET",
            url: "{{ route('office_boys.getManagers') }}",
            data: {
                employee_id: employee_id,
                manager_id: manager_id
            },
            success: function(res) {
                if (res.success == true) {
                    $(".appendUpdateManagerModal").html(res.view);
                    $("#showManagerModel").modal('show');
                    $('.select2').select2({
                        dropdownParent: $('#showManagerModel')
                    });
                }
            }
        });
    });

    $(document).on("click", ".updateManager", function() {
        var manager_id = $("#modal_manager_id").val();
        var employee_id = $("#modal_employee_id").val();
        $('.sub-btn-all').hide();
        $('.loading-btn-all').show();
        $("#modal_manager_id_error").empty();
        if (employee_id && manager_id) {
            $.ajax({
                method: "GET",
                url: "{{ route('office_boys.updateManager') }}",
                data: {
                    manager_id: manager_id,
                    employee_id: employee_id
                },
                success: function(res) {
                    $('.sub-btn-all').show();
                    $('.loading-btn-all').hide();
                    if (res.success == true) {
                        showJqueryConfirm(res.msg, 'green')
                        table.ajax.reload(null, false)
                        $("#showManagerModel").modal('hide');
                    }
                },
                error: function(xhr, status, error) {
                    $('.sub-btn-all').show();
                    $('.loading-btn-all').hide();
                    $("#modal_manager_id_error").html(error);
                }
            });
        } else {
            $('.sub-btn-all').show();
            $('.loading-btn-all').hide();
            $("#modal_manager_id_error").html('The manager field is required.');
        }
    });

    function selectInit() {
        setTimeout(() => {
            $('.select2').each(function() {
                $(this).select2({
                    // theme: 'bootstrap-5',
                    dropdownParent: $(this).parent(),
                });
            });
        }, 1000);
    }
    $(":input").inputmask();
</script>



<script>
    // currency rate on change
    $(document).on("change", "#currency", function() {
        var code = $(this).val();
        var salary = $("#salary").val();
        if (code && code == "USD") {
            convertCurrency(code, salary)
        } else {
            $("#currency_rate_after_conversion").html("")
        }

    });
    $(document).on("keyup", "#salary", function() {
        var code = $("#currency").val();
        var salary = $(this).val();
        if (code && code == "USD") {
            convertCurrency(code, salary)
        } else {
            $("#currency_rate_after_conversion").html("")
        }
    });

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
                    $("#currency_rate_after_conversion").html('<div class="spinner-grow" style="width: 12px;height: 12px !important;color: #e30b5c;" role="status"><span class="sr-only">Loading...</span></div>')
                },
                success: function(res) {
                    $("#conversion_amount_hidden").val(res.data.convertedAmount);
                    $("#conversion_rate").val(res.data.conversionRate)
                    $("#currency_rate_after_conversion").html("After Conversion: " + res.data.convertedAmountWithSymbol)
                },
                error: function(xhr, status, error) {
                    console.log(error)
                    console.log(xhr)
                }
            });
        }
    }
</script>

@endpush
