@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    @if(isset($users))
        <input type="hidden" id="page_url" value="{{ route('authorize_emails.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('authorize_emails.trashed') }}">
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
                            @if(isset($users))
                                <div class="dt-buttons flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('authorize_emails.trashed') }}" class="btn btn-label-danger me-1">
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
                                        title="Add Authorize Email"
                                        id="add-btn"
                                        data-url="{{ route('authorize_emails.store') }}"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#offcanvasAddAnnouncement"
                                        >
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </div>
                            @else
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('authorize_emails.index') }}" class="btn btn-success btn-primary">
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
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Email Title</th>
                                        <th style="width:300px">To Emails</th>
                                        <th style="width:300px">CC-Emails</th>
                                        <th>Status</th>
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

    <!-- Add Employment Status Modal -->
    <div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                        @csrf

                        <span id="edit-content">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="email_title">Email Title <span class="text-danger">*</span></label>
                                <select class="form-control" id="email_title" name="email_title">
                                    <option value="" selected>Select Email Title</option>
                                    <option value="promotion">Promotion</option>
                                    <option value="new_employee_info">New Employee</option>
                                    <option value="employee_termination">Employee Temination</option>
                                    <option value="employee_resignation">Employee Resignation</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="email_title_error" class="text-danger error"></span>
                            </div>
                            <div class="col-12 col-md-12 mt-3 select2-primary">
                                <label class="form-label" for="to_emails">To Emails <span class="text-danger">*</span></label>
                                <select id="to_email" class="form-control select2" multiple name="to_emails[]">
                                    @if(isset($users))
                                        <option value="to_employee">To Employee</option>
                                        <option value="to_ra">To RA</option>
                                        @foreach($users as $user)
                                            @php $department_name = ''; @endphp
                                            @if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->name))
                                                @php $department_name = '( '. $user->departmentBridge->department->name.' )'; @endphp
                                            @endif
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} {{ $department_name }}</option>
                                        @endforeach
                                    @endif
                                 </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="to_emails_error" class="text-danger error"></span>
                            </div>
                            <div class="col-12 col-md-12 mt-3 select2-primary">
                                <label class="form-label" for="cc_emails">CC Emails</label>
                                <select id="cc_emails" class="form-control select2" multiple name="cc_emails[]">
                                    @if(isset($users))
                                        <option value="to_employee">To Employee</option>
                                        <option value="to_ra">To RA</option>
                                        @foreach($users as $user)
                                            @php $department_name = ''; @endphp
                                            @if(isset($user->departmentBridge->department) && !empty($user->departmentBridge->department->name))
                                                @php $department_name = '( '. $user->departmentBridge->department->name.' )'; @endphp
                                            @endif
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }} {{ $department_name }}</option>
                                        @endforeach
                                    @endif
                                 </select>
                                 <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="cc_emails_error" class="text-danger error"></span>
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
    <!--/ Edit Employment Status Modal -->
@endsection
@push('js')
    <script>
        function selectInit(){
            setTimeout(() => {
                 $('.select2').each(function () {
                    $(this).select2({
                        // theme: 'bootstrap-5',
                        multiple:true,
                        dropdownParent: $(this).parent(),
                    });
                });
           }, 500);
        }

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
                    { data: 'email_title', name: 'email_title' },
                    { data: 'to_emails', name: 'to_emails' },
                    { data: 'cc_emails', name: 'cc_emails' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });


    </script>
@endpush
