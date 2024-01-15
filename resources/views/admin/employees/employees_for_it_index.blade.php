@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@push('styles')
@endpush

@section('content')
<input type="hidden" id="page_url" value="{{ route('employees_for_it') }}">

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
         <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card mt-4">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Employee</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th style="width:200px">Shift</th>
                                    <th>Employment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add New Employee Modal -->
            <div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="role-title mb-2" id="modal-label"></h3>
                            </div>
                            <!-- Add role form -->
                            <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                data-method="" data-modal-id="create-form-modal" id="create-form">
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
            <!-- Add New Employee Modal -->
        </div>
    </div>
</div>
@endsection
@push('js')
    <script>
        $('#create-form-modal').on('shown.bs.modal', function () {
            // Initialize select2 for all "select2" elements within the modal
            $('select2').each(function () {
            $(this).select2({
                    dropdownParent: $(this).parent(),
                });
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
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'role', name: 'role' },
                    { data: 'Department', name: 'Department' },
                    { data: 'shift', name: 'shift' },
                    { data: 'emp_status', name: 'emp_status' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
