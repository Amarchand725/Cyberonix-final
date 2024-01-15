@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
@endpush

@section('content')
<input type="hidden" id="page_url" value="{{ route('wfh_employees.index') }}">

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
                        <div class="dt-buttons btn-group flex-wrap">
                            <button
                                class="btn btn-secondary add-new btn-primary mx-3"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Add WFH Employees"
                                id="add-btn"
                                data-url="{{ route('wfh_employees.store') }}"
                                tabindex="0" aria-controls="DataTables_Table_0"
                                type="button" data-bs-toggle="modal"
                                data-bs-target="#create-form-modal"
                                >
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card mt-3">
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
                                    <th class="w-20">Shift</th>
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
                <div class="modal-dialog modal-md modal-dialog-top">
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

                                <span id="edit-content">
                                    <div class="row">
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="wfh_employees">Employees <span class="text-danger">*</span></label>
                                            <select id="wfh_employees" name="wfh_employees[]" multiple class="form-select select2">
                                                @if(isset($employees))
                                                    @foreach ($employees as $employee)
                                                        @php $department_name = ''; @endphp
                                                        @if(isset($employee->departmentBridge->department) && !empty($employee->departmentBridge->department->name))
                                                            @php $department_name = '( '. $employee->departmentBridge->department->name.' )'; @endphp
                                                        @endif
                                                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }} {{ $department_name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="wfh_employees_error" class="text-danger error"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="status">Status </label>
                                            <select id="status" name="status" class="form-control">
                                                <option value="">Active</option>
                                                <option value="0">In-Active</option>
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="status_error" class="text-danger error"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12 col-md-12">
                                            <label class="form-label" for="note">Note </label>
                                            <textarea name="note" id="note" rows="5" class="form-control" placeholder="Enter important note."></textarea>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="note_error" class="text-danger error"></span>
                                        </div>
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
                ajax: page_url+"?loaddata=yes",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'role', name: 'role' },
                    { data: 'Department', name: 'Department' },
                    { data: 'shift', name: 'shift' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
