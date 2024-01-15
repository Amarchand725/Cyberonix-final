@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
@endpush

@section('content')
@if(isset($temp))
<input type="hidden" id="page_url" value="{{ route('office_boys.index') }}">
@else
<input type="hidden" id="page_url" value="{{ route('office_boys.trashed') }}">
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
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        @if(isset($temp))
                        @can('office_boys-delete')
                        <div class="dt-buttons flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('office_boys.trashed') }}" class="btn btn-label-danger mx-4">
                                <span>
                                    <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                </span>
                            </a>
                        </div>
                        @endcan
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
                        <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('office_boys.index') }}" class="btn btn-success btn-primary mx-3">
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
        table.ajax.reload(null, false)
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


    $(":input").inputmask();
</script>
@endpush