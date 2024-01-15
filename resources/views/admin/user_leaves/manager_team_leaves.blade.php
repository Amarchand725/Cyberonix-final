@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
@endpush

@section('content')
@if(empty($url))
    <input type="hidden" id="page_url" value="{{ route('manager.team.leaves') }}">
@else
    <input type="hidden" id="page_url" value="{{ $url }}">
@endif

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
        </div>
        <br />
        <!-- Users List Table -->
        <div class="card input-checkbox">
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
                <div class="col-md-4 text-end">
                    <button disabled class="btn btn-success bluk-approve-btn me-2" data-url="{{ route('team.user_leaves.status', ['status' => 'approve']) }}"><i class="fa fa-check"></i>&nbsp; Approve</button>
                    <button disabled class="btn btn-danger bluk-approve-btn" data-url="{{ route('team.user_leaves.status', ['status' => 'reject']) }}"><i class="fa fa-times"></i>&nbsp; Reject</button>
                </div>
            </div>
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="col-md-4">
                    {{-- @if(isset($employees) && !empty($employees))
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                                    <option value="{{ URL::to('manager/team/leaves') }}">All Team Leaves</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ URL::to('manager/team/leaves/'.$employee->slug) }}" {{ $user->slug==$employee->slug?"selected":"" }} >
                                            {{ $employee->first_name }} {{ $employee->last_name }} ({{ $user->profile->employment_id??'-' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif --}}

                    @php $url = URL::to('manager/team/leaves') @endphp
                    @include('admin.layouts.employee-dropdown', ['employees' => $employees, 'user' => $user, 'url' => $url, 'type' => 'redirect'])
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
<script>
    function redirectPage(dropdown) {
        var selectedOption = dropdown.value;

        if (selectedOption !== '') {
            window.location.href = selectedOption;
        }
    }

    $(document).ready(function() {
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
    });

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
            ajax: page_url+"?loaddata=yes",
            columns: [
                { data: 'select', name: 'select', orderable: false, searchable: false },
                { data: 'user_id', name: 'user_id' },
                { data: 'start_at', name: 'start_at' },
                { data: 'duration', name: 'duration' },
                { data: 'behavior_type', name: 'behavior_type' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
