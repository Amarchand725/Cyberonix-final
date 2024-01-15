@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    @if(!isset($temp))
        <input type="hidden" id="page_url" value="{{ route('leave_types.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('leave_types.trashed') }}">
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
                            @if(!isset($temp))
                                <div class="dt-buttons flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('leave_types.trashed') }}" class="btn btn-label-danger mx-1">
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
                                        title="Add Leave Type"
                                        id="add-btn"
                                        data-url="{{ route('leave_types.store') }}"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasmodal"
                                        >
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </div>
                            @else
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('leave_types.index') }}" class="btn btn-success btn-primary mx-3">
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
                            <table class="dt-row-grouping table dataTable dtr-column table-border border-top data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Created at</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Offcanvas to add new user -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasmodal" aria-labelledby="offcanvasmodalLabel">
                    <div class="offcanvas-header">
                        <h5 id="modal-label" class="offcanvas-title"></h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                        <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="offcanvasmodal" id="create-form">
                            @csrf

                            <div id="edit-content">
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="name">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" placeholder="Enter leave type name" name="name">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="name_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="amount">Amount</label>
                                    <input type="number" class="form-control" id="amount" step="0.01" placeholder="Enter leave type amount" name="amount">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="amount_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="spacial_percentage">Spacial Percentage</label>
                                    <input type="number" class="form-control" id="spacial_percentage" min="1" max="100" maxlength="3" placeholder="Enter spacial percentage" name="spacial_percentage">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="spacial_percentage_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="" selected>Select Type</option>
                                        <option value="paid">Paid</option>
                                        <option value="unpaid">Un-paid</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="type_error" class="text-danger error"></span>
                                </div>
                            </div>
                            
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
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $('#spacial_percentage').on('keyup', function(){
            var val = $(this).val();
            if(val > 100){
                $('#spacial_percentage_error').text('You have allowed max 100%.');
                $(this).val('');
                return false;
            }else{
                $('#spacial_percentage_error').text('');
            }
        });
    </script>

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
                    {data: 'type', name:'type'},
                    {data: 'amount', name:'amount'},
                    {data: 'status', name:'status'},
                    {data: 'created_at', name:'created_at'},
                    {data: 'action', name:'action', orderable:false, searchable:false}
                ]
            });
        });
    </script>
@endpush
