@extends('admin.layouts.app')
@section('title', $title. ' - ' . appName())

@section('content')
@if(isset($data['parent_departments']))
    <input type="hidden" id="page_url" value="{{ route('departments.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('departments.trashed') }}">
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
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        @if(isset($data['parent_departments']))
                            <div class="dt-buttons flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('departments.trashed') }}" class="btn btn-label-danger mx-1">
                                    <span>
                                        <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                    </span>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button
                                    class="btn btn-secondary add-new btn-primary mx-3"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Add New Department"
                                    id="add-btn"
                                    data-url="{{ route('departments.store') }}"
                                    type="button"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasAddDepartment"
                                    >
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add New</span>
                                    </span>
                                </button>
                            </div>
                        @else
                            <div class="dt-buttons btn-group flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('departments.index') }}" class="btn btn-success btn-primary mx-3">
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
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column data_table table-responsive">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Department</th>
                                    <th>Parent Department</th>
                                    <th class="w-20">Manager</th>
                                    <th style="width: 97px;" aria-label="Role: activate to sort column ascending">Created At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Offcanvas to add new user -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddDepartment" aria-labelledby="offcanvasAddDepartmentLabel">
                <div class="offcanvas-header">
                    <h5 id="modal-label" class="offcanvas-title">Add Department</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddDepartment">
                        @csrf

                        <span id="edit-content">
                            <div class="mb-3 fv-plugins-icon-container">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" placeholder="Enter department name" name="name">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="name_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="parent_department_id">Parent Department <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <select id="parent_department_id" name="parent_department_id" class="form-control">
                                        <option value="">Select parent department</option>
                                        @if(isset($data['parent_departments']))
                                            @foreach ($data['parent_departments'] as $department)
                                                <option value="{{ $department->id }}" {{ $department->name=='Main Department'?'selected':'' }}>{{ $department->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="parent_department_id_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="manager_id">Manager</label>
                                <div class="position-relative">
                                    <select id="manager_id" name="manager_id" class="form-control">
                                        <option value="">Select manager</option>
                                        @if(isset($data['department_managers']))
                                            @foreach ($data['department_managers'] as $manager)
                                                <option value="{{ $manager['id'] }}">{{ $manager['first_name'] }} {{ $manager['last_name'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span id="manager_id_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">Description ( <small>Optional</small> )</label>
                                <textarea name="description" id="description" class="form-control" placeholder="Enter description"></textarea>
                            </div>
                        </span>

                        <div class="col-12 mt-3 action-btn">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1 submitBtn">Submit</button>
                            </div>
                            <div class="demo-inline-spacing loading-btn" style="display: none;">
                                <button class="btn btn-primary waves-effect waves-light" type="button" disabled="">
                                  <span class="spinner-border me-1" role="status" aria-hidden="true"></span>
                                  Loading...
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- View Department Details Modal -->
            <div class="modal fade" id="dept-details-modal" tabindex="-1" aria-hidden="true">
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
            <!-- View Department Details Modal -->
        </div>
    </div>
</div>
@endsection
@push('js')
    <script type="text/javascript">
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        $(document).ready(function(){
            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing:true,
                serverSide:true,
                ajax: page_url+"?loaddata=yes",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'name', name:'name'},
                    {data: 'parent_department_id', name:'parent_department_id'},
                    {data: 'manager_id', name:'manager_id'},
                    {data: 'created_at', name:'created_at'},
                    {data: 'status', name:'status'},
                    {data: 'action', name:'action', orderable:false, searchable:false}
                ]
            });
        });
    </script>
@endpush
