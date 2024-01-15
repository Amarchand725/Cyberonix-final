@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())
@push('styles')
@endpush
@section('content')
    @if(!isset($temp))
        <input type="hidden" id="page_url" value="{{ route('positions.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('positions.trashed') }}">
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
                                <div class="dt-buttons btn-group flex-wrap">
                                    <button
                                        class="btn btn-secondary add-new btn-primary mx-3"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Add New Postion"
                                        id="add-btn"
                                        data-url="{{ route('positions.store') }}"
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
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('positions.index') }}" class="btn btn-success btn-primary">
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
                            <table class="dt-row-grouping table dataTable dtr-column table border-top data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Title</th>
                                        <th>Description</th>
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
                        <h5 id="modal-label" class="offcanvas-title">Add Position</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                        <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="offcanvasmodal" id="create-form">
                            @csrf

                            <div id="edit-content">
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" placeholder="Enter position title" name="title">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="title_error" class="text-danger error"></span>
                                </div>
                                <div class="mb-3 fv-plugins-icon-container">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description" placeholder="Enter description"></textarea>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="description_error" class="text-danger error"></span>
                                </div>

                                <!--<div class="mb-4">-->
                                <!--    <label class="form-label" for="status">Select Status</label>-->
                                <!--    <select id="status" name="status" class="form-control">-->
                                <!--        <option value="" selected>Select Status</option>-->
                                <!--        <option value="1">Active</option>-->
                                <!--        <option value="0">De-active</option>-->
                                <!--    </select>-->
                                <!--</div>-->
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
                    {data: 'title', name:'title'},
                    {data: 'description', name:'description'},
                    {data: 'status', name:'status'},
                    {data: 'created_at', name:'created_at'},
                    {data: 'action', name:'action', orderable:false, searchable:false}
                ]
            });
        });
    </script>
@endpush
