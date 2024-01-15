@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    @if(isset($employees))
        <input type="hidden" id="page_url" value="{{ route('employee_letters.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('employee_letters.trashed') }}">
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
                    @can('employee_letters-create')
                        <div class="col-md-6">
                        <div class="d-flex justify-content-end align-item-center mt-4">
                            @if(isset($employees))
                                <div class="dt-buttons flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('employee_letters.trashed') }}" class="btn btn-label-danger mx-1">
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
                                        title="Add Employee Letter"
                                        type="button"
                                        class="btn btn-secondary add-new btn-primary mx-3"
                                        id="add-btn"
                                        data-url="{{ route('employee_letters.store') }}"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#offcanvasAddAnnouncement"
                                        >
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New Letter</span>
                                        </span>
                                    </button>
                                </div>
                            @else 
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('employee_letters.index') }}" class="btn btn-success btn-primary mx-3">
                                        <span>
                                            <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All Records</span>
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endcan
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No#</th>
                                        <th scope="col">Employee</th>
                                        <th scope="col" style="width:20%;">Title</th>
                                        <th scope="col">Effective Date</th>
                                        <th scope="col">Validity Date</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Actions</th>
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
                                <label class="form-label" for="employee_id">Employee <span class="text-danger">*</span></label>
                                <select class="form-control" id="employee_id" name="employee_id">
                                    <option value="" selected>Select Employee</option>
                                    @if(isset($employees))
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="employee_id_error" class="text-danger error"></span>
                            </div>
                            <div class="col-12 col-md-12 mt-2">
                                <label class="form-label" for="title">Title <span class="text-danger">*</span></label>
                                <select class="form-control" id="title" name="title">
                                    <option value="" selected>Select Letter</option>
                                    <option value="joining_letter">Joining Letter</option>
                                    <option value="vehical_letter">Vehicle Letter</option>
                                    <option value="promotion_letter">Promotion Letter</option>
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="template_id_id_error" class="text-danger error"></span>
                            </div>
                            <div class="col-12 col-md-12 mt-3">
                                <label class="form-label" for="effective_date">Effective Date <span class="text-danger">*</span></label>
                                <input type="date" name="effective_date" id="effective_date" class="form-control" />

                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="effective_date_error" class="text-danger error"></span>
                            </div>
                            <div class="col-12 col-md-12 mt-3 validity-date">
                                
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
    <div class="modal fade modal-add-new-cc" id="view-template-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl ">
            <div class="modal-content p-0">
                <div class="modal-header p-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
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
        $(document).on('change', '#title', function(){
            var title = $(this).val();
            if(title=='joining_letter'){
                $('.is-vehicle').html("");  
                $('.vehicle-content').html("");
                $('.validity-date').html("");
                
                var html = '<label class="form-label" for="is_vehicle">Vehicle <span class="text-danger">*</span></label>'+
                            '<select class="form-control is_vehicle" id="is_vehicle" name="is_vehicle">'+
                                '<option value="1">Yes</option>'+
                                '<option value="0" selected>No</option>'+
                            '</select>'+
                            '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                            '<span id="template_id_id_error" class="text-danger error"></span>';
                            
                            $('.is-vehicle').html(html);
                            
                var vhtml = '<label class="form-label" for="validity_date">Validity Date </label>'+
                            '<input type="date" name="validity_date" id="validity_date" class="form-control" />'+
                            '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                            '<span id="validity_date_error" class="text-danger error"></span>';
                            
                            $('.validity-date').html(vhtml);
            }else if(title=='vehical_letter'){
                $('.is-vehicle').html("");  
                $('.validity-date').html("");
                $('.vehicle-content').html("");
                
                var html = '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_name">Vehicle Name <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" placeholder="Enter vehicle name">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_name_error" class="text-danger error"></span>'+
                            '</div>'+
                            '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_cc">Vehicle Engine Capacity (CC) <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_cc" name="vehicle_cc" placeholder="Enter vehicle engine cc">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_cc_error" class="text-danger error"></span>'+
                            '</div>';
                $('.vehicle-content').html(html);
            }else{
                $('.is-vehicle').html("");  
                $('.vehicle-content').html("");
                $('.validity-date').html("");
            }
        });
        
        $(document).on('change', '.is_vehicle', function(){
            var is_vehicle = $(this).val();
            if(is_vehicle==1){
                var html = '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_name">Vehicle Name <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_name" name="vehicle_name" placeholder="Enter vehicle name">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_name_error" class="text-danger error"></span>'+
                            '</div>'+
                            '<div class="col-12 col-md-12 mt-2">'+
                                '<label class="form-label" for="vehicle_cc">Vehicle Engine Capacity (CC) <span class="text-danger">*</span></label>'+
                                '<input type="text" class="form-control" id="vehicle_cc" name="vehicle_cc" placeholder="Enter vehicle engine cc">'+
                                '<div class="fv-plugins-message-container invalid-feedback"></div>'+
                                '<span id="vehicle_cc_error" class="text-danger error"></span>'+
                            '</div>';
                $('.vehicle-content').html(html);
            }else{
                $('.vehicle-content').html("");
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
                    { data: 'employee_id', name: 'employee_id' },
                    { data: 'title', name: 'title' },
                    { data: 'effective_date', name: 'effective_date' },
                    { data: 'validity_date', name: 'validity_date' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
