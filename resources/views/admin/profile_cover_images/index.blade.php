@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@section('content')
@if(!isset($temp))
    <input type="hidden" id="page_url" value="{{ route('profile_cover_images.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('profile_cover_images.trashed') }}">
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
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('profile_cover_images.trashed') }}" class="btn btn-label-danger mx-1">
                                    <span>
                                        <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">All Trashed Records</span>
                                    </span>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button
                                    class="btn btn-secondary add-new btn-primary mx-3"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Add New Cover Image"
                                    id="add-btn"
                                    data-url="{{ route('profile_cover_images.store') }}"
                                    tabindex="0"
                                    aria-controls="DataTables_Table_0"
                                    type="button"
                                    data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasAddCoverImage"
                                    >
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add New</span>
                                    </span>
                                </button>
                            </div>
                        @else
                            <div class="dt-buttons btn-group flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('profile_cover_images.index') }}" class="btn btn-success btn-primary mx-3">
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
                                    <th>Image</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
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
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddCoverImage" aria-labelledby="offcanvasAddCoverImageLabel">
                <div class="offcanvas-header">
                    <h5 id="offcanvasAddCoverImageLabel" class="offcanvas-title">Add Profile Cover Image</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework submitBtnWithFileUpload"  data-method="post" data-modal-id="offcanvasAddCoverImage" id="create-form" enctype="multipart/form-data" >
                        @csrf
                        
                        <span id="edit-content">
                            <div class="mb-3 fv-plugins-icon-container">
                                <label class="form-label" for="uploadImage">Cover Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="uploadImage" name="image">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="image_error" class="text-danger error"></span>
                            </div>
                            <div class="mb-3 mt-2 fv-plugins-icon-container">
                                <img id="imagePreview" src="{{ asset('public/admin/default.png') }}" alt="Image Preview" style="width:100px; height:80px">
                            </div>
                            
                        </span>
                        
                        <div class="col-12 mt-3 action-btn">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
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
        $(document).ready(function() {
            // Listen for file input changes
            $("#uploadImage").change(function() {
                readURL(this);
            });
        
            // Function to display the image preview
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
        
                    reader.onload = function(e) {
                        // Set the src attribute of the image with the data URL
                        $("#imagePreview").attr("src", e.target.result);
                    };
        
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
    
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
                    {data: 'image', name:'image'},
                    {data: 'created_by', name:'created_by'},
                    {data: 'created_at', name:'created_at'},
                    {data: 'status', name:'status'},
                    {data: 'action', name:'action', orderable:false, searchable:false}
                ]
            });
        });
    </script>
@endpush
