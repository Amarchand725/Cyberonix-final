@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@push('styles')
@endpush

@section('content')
@if(isset($temp))
    <input type="hidden" id="page_url" value="{{ route('employment_status.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('employment_status.trashed') }}">
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
                        @if(isset($temp))
                            <div class="dt-buttons flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('employment_status.trashed') }}" class="btn btn-label-danger me-1">
                                    <span>
                                        <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">All Trashed Records</span>
                                    </span>
                                </a>
                            </div>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Add New Employment Status"
                                    id="add-btn"
                                    data-url="{{ route('employment_status.store') }}"
                                    class="btn btn-success add-new btn-primary mx-3"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target="#create-form-modal"
                                    >
                                    <span>
                                        <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                        <span class="d-none d-sm-inline-block">Add New </span>
                                    </span>
                                </button>
                            </div>
                        @else
                            <div class="dt-buttons btn-group flex-wrap">
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('employment_status.index') }}" class="btn btn-success btn-primary mx-3">
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
        <div class="card">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Name</th>
                                    <th>Preview</th>
                                    <th>Description</th>
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
<div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2" id="modal-label"></h3>
          </div>
          <form id="create-form" data-method="" data-modal-id="create-form-modal" class="row g-3">
            @csrf

            <div id="edit-content">
                <div class="col-12 col-md-12">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Permanant" />
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="name_error" class="text-danger error"></span>
                </div>
                <div class="col-12 col-md-12">
                    <label class="form-label" for="class_name">Class <span class="text-danger">*</span></label>
                    <select name="class" class="form-control" id="class_name">
                        <option value="" selected>Select class</option>
                        <option value="purple"> Purple </option>
                        <option value="success"> Success </option>
                        <option value="info"> Info </option>
                        <option value="warning"> Warning </option>
                        <option value="primary"> Primary </option>
                        <option value="danger"> Danger </option>
                    </select>
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="class_error" class="text-danger error"></span>
                </div>
                <div class="col-12 col-md-12">
                    <div class="note note-warning p-2 mt-3">
                        <div class="demo-inline-spacing">
                            <div class="card-body">
                                <div class="alert alert-warning" role="alert">
                                    <span class="badge bg-danger" id="badge-class-label">Terminated</span> This will be the badge of the employee
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <label class="form-label" for="description">Description ( <small>Optional</small> )</label>
                    <textarea class="form-control" name="details" placeholder="Enter description">{{ old('description') }}</textarea>
                </div>
            </div>
            
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
<!--/ Add Employment Status Modal -->
@endsection
@push('js')
    <script>
        $(document).on('keyup', '#name', function(){
            var label = $(this).val();
            var class_name = $('#class_name').val();

            $('#badge-class-label').html(label);
            $('#badge-class-label').addClass(class_name);
        });

        $(document).on('change', '#class_name', function(){
            var class_name = $(this).val();
            var new_class = 'badge bg-'+class_name;
            $('#badge-class-label').removeClass().addClass(new_class);
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
                    { data: 'class', name: 'class' },
                    { data: 'description', name: 'description' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
