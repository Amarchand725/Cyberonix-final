@extends('admin.layouts.app')

@section('title', $title.' -  ' . appName())

@section('content')
<input type="hidden" id="page_url" value="{{ route('permissions.index') }}">
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
                    <div class="dt-buttons btn-group flex-wrap float-end mt-4">
                        <button
                            id="add-btn"
                            data-toggle="tooltip" data-placement="top" title="Add Permission"
                            data-url="{{ route('permissions.store') }}"
                            class="btn add-new btn-primary mb-3 mb-md-0 mx-3"
                            tabindex="0" aria-controls="DataTables_Table_0"
                            type="button" data-bs-toggle="modal"
                            data-bs-target="#addPermissionModal">
                            <span>
                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block"> Add Permission </span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Permission URL</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <!-- Add Permission Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="mb-2" id="modal-label">Add New Permission</h3>
                                <p class="text-muted">Permissions you may use and assign to your users.</p>
                            </div>
                            <form id="create-form" data-modal-id="addPermissionModal" class="row">
                                @csrf

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="name">Permission Name <span class="text-danger">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Permission Name" autofocus />
                                    <span id="name_error" class="text-danger"></span>
                                    <input type="hidden" id="label"/>
                                    <span id="label_error" class="text-danger error"></span>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label" for="custom">Custom Permission <small>If needed</small></label>
                                    <input type="text" id="custom" name="custom" class="form-control" placeholder="Permission Name" autofocus />
                                    <span id="custom_error" class="text-danger"></span>
                                    <input type="hidden" id="label"/>
                                    <span id="label_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="card-body border-top p-9">
                                        <!--begin::Input group-->
                                        <div class="row mb-6">

                                            <!--begin::Col-->
                                            <div class="col-lg-8 fv-row">
                                                <!-- Default checkbox -->
                                                <div class="col-lg-3">
                                                    <span class="text-danger">*</span>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="all" id="checkAll"/>
                                                        <label class="form-check-label" for="checkAll"> <strong>All</strong> </label>
                                                    </div>
                                                </div>
                                                <!-- Default checkbox -->
                                                <div class="col-lg-3 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox" value="list" id="list" checked/>
                                                        <label class="form-check-label" for="list"> <strong>List</strong></label>
                                                    </div>
                                                </div>

                                                <!-- Checked checkbox -->
                                                <div class="col-lg-3 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox" value="create" id="create"/>
                                                        <label class="form-check-label" for="create"> <strong>Create</strong></label>
                                                    </div>
                                                </div>

                                                <!-- Checked checkbox -->
                                                <div class="col-lg-3 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox" value="edit" id="edit"/>
                                                        <label class="form-check-label" for="edit"> <strong>Edit</strong></label>
                                                    </div>
                                                </div>

                                                <!-- Checked checkbox -->
                                                <div class="col-lg-3 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox" value="delete" id="delete"/>
                                                        <label class="form-check-label" for="delete"> <strong>Delete</strong></label>
                                                    </div>
                                                </div>

                                                <!-- Checked checkbox -->
                                                <div class="col-lg-3 mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox" value="status" id="status"/>
                                                        <label class="form-check-label" for="status"> <strong>Status</strong></label>
                                                    </div>
                                                </div>

                                                <span id="permissions_error" class="text-danger"></span>
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>

                                <div class="col-12 mt-3 action-btn">
                                    <div class="demo-inline-spacing sub-btn">
                                        <button type="submit" data-url="{{ route('permissions.store') }}" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
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
            <!--/ Add Permission Modal -->
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
	$("#checkAll").click(function () {
		$('input:checkbox').not(this).prop('checked', this.checked);
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
                    { data: 'label', name: 'label' },
                    { data: 'permissions', name: 'permissions' },
                    { data: 'permission_url', name: 'permission_url' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
</script>
@endpush
