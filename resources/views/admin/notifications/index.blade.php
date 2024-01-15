@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
    <style>
        p {
            text-align: left;
        }
    </style>
@endpush

@section('content')
<input type="hidden" id="page_url" value="{{ route('notifications.index') }}">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card input-checkbox">
                <div class="card-header d-flex justify-content-between">
                    <h5 class="card-title">All Notifications</h5>
                    <button disabled class="btn btn-outline-danger waves-effect bluk-delete-btn" data-url="{{ route('notifications.delete') }}"><i class="ti ti-trash ti-xs me-1"></i> Delete</button>
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="form-check">
                                              <input class="form-check-input" type="checkbox" value="" id="selectAll">
                                            </div>
                                        </th>
                                        <th scope="col">User</th>
                                        <th scope="col" style="width: 50%;">Title</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Action</th>
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
                        <button
                            type="reset"
                            class="btn btn-label-primary btn-reset"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
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
    $(document).ready(function() {
        // Event handler for the "Select All" checkbox
        $(document).on('click', '#selectAll', function() {
            // Check/uncheck all checkboxes based on the Select All checkbox
            $(this).parents('.input-checkbox').find(".checkbox").prop("checked", $(this).prop("checked"));

            // var anyCheckboxChecked = $('.input-checkbox .checkbox:not(selectAll):checked').length > 0;
            var total_checked_length = $(this).parents('.input-checkbox').find(".checkbox:checked").length;

            if (total_checked_length > 0) {
                $(this).parents('.input-checkbox').find('.bluk-delete-btn').prop('disabled', !$(this).prop('checked'));
            } else {
                $(this).parents('.input-checkbox').find('.bluk-delete-btn').prop('disabled', true);
            }
        });

        // Individual checkbox click event
        $(document).on('click', ".checkbox", function() {
            // Check the Select All checkbox if all checkboxes are checked
            var total_checkboxes_length = $(this).parents('.input-checkbox').find(".checkbox").length;
            var total_checked_length = $(this).parents('.input-checkbox').find(".checkbox:checked").length;

            if (total_checked_length > 0 && total_checked_length < total_checkboxes_length) {
                $(this).parents('.input-checkbox').find("#selectAll").prop("checked", false);
                $(this).parents('.input-checkbox').find(".bluk-delete-btn").prop("disabled", false);
            } else if (total_checked_length === total_checkboxes_length) {
                $(this).parents('.input-checkbox').find("#selectAll").prop("checked", true);
                $(this).parents('.input-checkbox').find(".bluk-delete-btn").prop("disabled", !$(this).prop("checked"));
            } else {
                $(this).parents('.input-checkbox').find("#selectAll").prop("checked", false);
                $(this).parents('.input-checkbox').find(".bluk-delete-btn").prop("disabled", true);
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
                { data: 'notifiable_id', name: 'notifiable_id' },
                { data: 'title', name: 'title' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
