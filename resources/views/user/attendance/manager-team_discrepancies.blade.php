@extends('admin.layouts.app')
@section('title', $title .' -  ' . appName())

@section('content')
@if(empty($url))
    <input type="hidden" id="page_url" value="{{ route('manager.team.discrepancies') }}">
@else
    <input type="hidden" id="page_url" value="{{ $url }}">
@endif

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card mb-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
            </div>
        </div>
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
                    <button disabled class="btn btn-success bluk-approve-btn me-2" data-url="{{ route('team.discrepancy.status', ['status' => 'approve']) }}"><i class="fa fa-check"></i>&nbsp; Approve</button>
                    <button disabled class="btn btn-danger bluk-approve-btn" data-url="{{ route('team.discrepancy.status', ['status' => 'reject']) }}"><i class="fa fa-times"></i>&nbsp; Reject</button>
                </div>
            </div>
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="col-md-4">
                    @php $url = URL::to('manager/team/discrepancies') @endphp
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
                                    <th>Attendance Date</th>
                                    <th>Type</th>
                                    <th>Additional</th>
                                    <th style="width: 97px;" aria-label="Role: activate to sort column ascending">Status</th>
                                    <th>Applied At</th>
                                    <th>Action</th>
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

<!-- Add Employment Status Modal -->
<div class="modal fade" id="view-reason-modal" tabindex="-1" aria-hidden="true">
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
<!--/ Edit Employment Status Modal -->

<div class="modal fade" id="view-discrepancy-details-modal" tabindex="-1" aria-hidden="true">
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

            // var anyCheckboxChecked = $('.input-checkbox .checkbox:not(selectAll):checked').length > 0;
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
                { data: 'date', name: 'date' },
                { data: 'type', name: 'type' },
                { data: 'is_additional', name: 'is_additional' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
