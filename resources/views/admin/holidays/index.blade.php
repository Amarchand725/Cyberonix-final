@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@push('styles')
@endpush

@section('content')
@if($title=='All Holidays')
    <input type="hidden" id="page_url" value="{{ route('holidays.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('holidays.trashed') }}">
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
                        @if($title=='All Holidays')
                            <div class="dt-buttons flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('holidays.trashed') }}" class="btn btn-label-danger mx-1">
                                    <span>
                                        <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                    </span>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Official Holidays"
                                    type="button"
                                    class="btn btn-secondary add-new btn-primary mx-3"
                                    id="add-btn"
                                    data-url="{{ route('holidays.store') }}"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target="#offcanvasAddAnnouncement"
                                    >
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add Holiday</span>
                                    </span>
                                </button>
                            </div>
                        @else
                            <div class="dt-buttons btn-group flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('holidays.index') }}" class="btn btn-success btn-primary mx-3">
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
        <div class="card input-checkbox">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Start At</th>
                                    <th>End At</th>
                                    <th>Off Days</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
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

 <!-- Add Holiday Modal -->
<div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-top modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework"  data-method="post" data-modal-id="offcanvasAddAnnouncement" id="create-form" enctype="multipart/form-data" >
                    @csrf

                    <span id="edit-content">
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter name here"/>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="name_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="d-block form-label">Holiday Type </label>
                                <select name="type" id="type" class="form-control check-type">
                                    <option value="universal" selected>Universal</option>
                                    <option value="customizable">Customizable</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="type_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row mt-2 d-none" id="customizeEmployeesRow">
                            <div class="col-md-12">
                                <label class="form-label" for="customize_employees">Customize Employees</label>
                                {{-- Gett Employees --}}
                                @if($title=='All Holidays')
                                    @include('admin.layouts.employee-dropdown', ['employees' => $employees, 'type' => 'filter'])
                                @endif
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="customize_employees_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label" for="start_at">Start Date <span class="text-danger">*</span></label>
                                <input type="date" id="start_at" name="start_at" class="form-control" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="start_at_error" class="text-danger error"></span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label class="form-label" for="end_at">End Date <span class="text-danger">*</span></label>
                                <input type="date" id="end_at" name="end_at" class="form-control" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="end_at_error" class="text-danger error"></span>
                            </div>
                        </div>

                        <div class="col-12 col-md-12 mt-3">
                            <label class="form-label" for="description">description <span class="text-danger">*</span></label>
                            <textarea class="form-control" rows="5" name="description" id="description" placeholder="Enter description here">{{ old('description') }}</textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="description_error" class="text-danger error"></span>
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
<!-- Add Holiday Modal -->

 <!-- Holiday Details Modal -->
 <div class="modal fade" id="view-details-modal" tabindex="-1" aria-hidden="true">
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
<!-- Holiday Details Modal -->

@endsection
@push('js')
    <script>
        $(document).on('change', '.check-type', function () {
            // Check if the "Customizable" radio button is selected
            if ($(this).val() === 'customizable') {
                // Show the "Customize Employees" row
                $('#customizeEmployeesRow').removeClass('d-none');
                $('.form-select').each(function() {
                    $(this).select2({
                        dropdownParent: $(this).parent(),
                    });
                });
            } else {
                // Hide the "Customize Employees" row
                $('#customizeEmployeesRow').addClass('d-none');

                $('.form-select').each(function() {
                    $(this).select2({
                        dropdownParent: $(this).parent(),
                    });
                });
            }
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
                    { data: 'name', name: 'name' },
                    { data: 'type', name: 'type' },
                    { data: 'start_at', name: 'start_at' },
                    { data: 'end_at', name: 'end_at' },
                    { data: 'off_days', name: 'off_days' },
                    { data: 'created_by', name: 'created_by' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
