@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
@endpush

@section('content')
<input type="hidden" id="page_url" value="{{ route(Route::currentRouteName()) }}">
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
            </div>
            <div class="row p-3">
                <div class="col-md-3 mb-3">
                    <label for="">Department</label>
                    <select name="department" id="department" class="select2 form-select department">
                        <option value="all">All</option>
                        @if(!empty(getAllDepartments()))
                            @foreach(getAllDepartments() as $department)
                                <option value="{{$department->id ?? ''}}">{{$department->name ?? ''}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Select Team Employee</label>
                    <select class="select2 form-select team">
                        <option value="">Select Employee</option>
                        @if(!empty(getUsersList()))
                            @foreach(getUsersList() as $user)
                                <option value="{{$user->id ?? ''}}">{{getUserName($user) ?? ''}} ({{ $user->profile->employment_id??'-' }})</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Month</label>
                    <input type="text"  class="form-control month" id="start" name="start" min="" value="" />
                    <input type="hidden" class="month-hidden">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="">Status</label>
                    <select name="leave_status" id="leave_status" class="select2 form-select leave_status">
                        <option value="all">All</option>
                        @if(!empty(getLeaveStatuses()))
                        @foreach(getLeaveStatuses() as $leaveStatus)
                        <option value="{{$leaveStatus->id ?? ''}}">{{$leaveStatus->name ?? ''}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
        </div>
        <br />
        <!-- Users List Table -->
        <div class="card input-checkbox">
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="col-md-8">
                    <span class="card-title mb-0">
                        <!-- user show a tag -->
                    </span>
                </div>
                <div class="col-md-4 text-end">
                    <button disabled class="btn btn-success bluk-approve-btn me-2" data-url="{{ route('team.user_leaves.status', ['status' => 'approve']) }}"><i class="fa fa-check"></i>&nbsp; Approve</button>
                    <button disabled class="btn btn-danger bluk-approve-btn" data-url="{{ route('team.user_leaves.status', ['status' => 'reject']) }}"><i class="fa fa-times"></i>&nbsp; Reject</button>
                </div>
            </div>


            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="selectAll">
                                        </div>
                                    </th>
                                    <th>Employee</th>
                                    <th style="width:150px">Date</th>
                                    <th>Days</th>
                                    <th>Behavior</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Applied At</th>
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

<div class="modal fade" id="view-leave-details-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <span id="show-content"></span>
            </div>
        </div>
    </div>
</div>

@endsection
@push('js')
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $(document).ready(function() {
        loadPageData()
        // Event handler for the "Select All" checkbox
        $(document).on('click', '#selectAll', function() {
            // Check/uncheck all checkboxes based on the Select All checkbox
            $(this).parents('.input-checkbox').find(".checkbox").prop("checked", $(this).prop("checked"));

            var total_checked_length = $(this).parents('.input-checkbox').find(".checkbox:checked").length;

            if (total_checked_length > 0) {
                $(this).parents('.input-checkbox').find('.bluk-approve-btn').prop('disabled', !$(this).prop('checked'));
            } else {
                $(this).parents('.input-checkbox').find('.bluk-approve-btn').prop('disabled', true);
            }
        });
        // Individual checkbox click event
        $(document).on('click', ".checkbox", function() {
            // Check the Select All checkbox if all checkboxes are checked
            var total_checkboxes_length = $(this).parents('.input-checkbox').find(".checkbox").length;
            var total_checked_length = $(this).parents('.input-checkbox').find(".checkbox:checked").length;

            if (total_checked_length > 0 && total_checked_length < total_checkboxes_length) {
                $(this).parents('.input-checkbox').find("#selectAll").prop("checked", false);
                $(this).parents('.input-checkbox').find(".bluk-approve-btn").prop("disabled", false);
            } else if (total_checked_length === total_checkboxes_length) {
                $(this).parents('.input-checkbox').find("#selectAll").prop("checked", true);
                $(this).parents('.input-checkbox').find(".bluk-approve-btn").prop("disabled", !$(this).prop("checked"));
            } else {
                $(this).parents('.input-checkbox').find("#selectAll").prop("checked", false);
                $(this).parents('.input-checkbox').find(".bluk-approve-btn").prop("disabled", true);
            }
        });
        $(document).on("change", ".team", function() {
            loadPageData()
        });
        $('#start').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            // startDate: monthlyReportStartYearMonth,
            endDate: new Date(),
        }).on('changeMonth', function(e) {
            var formattedDate = e.date.toLocaleString('default', { month: 'long' }) + ' ' + e.date.getFullYear();
            $(this).val(formattedDate);
            var submittedFormat = e.date.getFullYear() + '-' + String(e.date.getMonth() + 1).padStart(2, '0');
            $('.month-hidden').val(submittedFormat);
            loadPageData();
        });
        $(document).on("change", ".department", function() {
            var id = $(this).val();
            $.ajax({
                url: "{{route('team.leavesGetMembersOfDepartment')}}",
                method: 'GET',
                data: {
                    id: id,
                },
                beforeSend: function() {
                    $(".team").empty()
                },
                success: function(res) {
                    if (res.success == true) {
                        // team
                        $(".team").append('<option value="">All<option>')
                        $.each(res.data, function(key, value) {
                            var option = '<option value="' + value.id + '"> ' + value.name + ' </option>'
                            if (value.id) {
                                $(".team").append(option)
                            }
                        });
                    } else {
                        $(".team").empty()
                    }
                },
                error: function(xhr, status, error) {
                    console.log("error " + error)
                    console.log("xhr " + xhr)
                    console.log("status " + status)
                }
            });
            loadPageData()
        });
        $(document).on("change", ".leave_status", function() {
            loadPageData()
        });
    });
    //datatable
    function loadPageData() {
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        var page_url = $('#page_url').val();
        var table = $('.data_table').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": true,
            "smart": true,
            "pageLength": 10,
            ajax: {
                url: page_url + "?loaddata=yes",
                type: "GET",
                data: function(d) {
                    d.keyword = $('.keyword').val();
                    d.team = $('.team').val();
                    d.month = $('.month-hidden').val();
                    d.department = $('.department').val();
                    d.leave_status = $('.leave_status').val();
                    d.search = $('input[type="search"]').val();
                },
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(error);
                    console.log(code);
                }
            },
            "initComplete": function(settings, json) {},
            columns: [{
                    data: 'select',
                    name: 'select',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'start_at',
                    name: 'start_at'
                },
                {
                    data: 'duration',
                    name: 'duration'
                },
                {
                    data: 'behavior_type',
                    name: 'behavior_type'
                },
                {
                    data: 'department',
                    name: 'department'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }
    //datatable
</script>
@endpush
