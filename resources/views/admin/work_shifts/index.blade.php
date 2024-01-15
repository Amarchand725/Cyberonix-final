@extends('admin.layouts.app')
@section('title', 'Work Shifts - ' . appName())

@section('content')
@if(!isset($temp))
    <input type="hidden" id="page_url" value="{{ route('work_shifts.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('work_shifts.trashed') }}">
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
                                <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('work_shifts.trashed') }}" class="btn btn-label-danger mx-1">
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
                                    title="Add New Shift"
                                    id="add-btn"
                                    data-url="{{ route('work_shifts.store') }}"
                                    class="btn btn-success add-new btn-primary mx-3"
                                    data-url="{{ route('employees.store') }}"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button"
                                    data-bs-toggle="modal"
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
                                <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('work_shifts.index') }}" class="btn btn-success btn-primary mx-3">
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
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th class="w-20">Name</th>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
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

<!-- Add New Work SHift Modal -->
<div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2" id="modal-label"></h3>
          </div>
          <form id="create-form" class="row g-3" data-method="" data-modal-id="create-form-modal">
            @csrf

            <span id="edit-content">
                <div class="mb-3 fv-plugins-icon-container">
                    <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" value="" placeholder="Enter Working Shift Name e.g Night" name="name">
                    <div class="fv-plugins-message-container invalid-feedback"></div>
                    <span id="name_error" class="text-danger error"></span>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="mb-3 fv-plugins-icon-container">
                            <label class="form-label" for="start_date">Start Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="start_date" value="{{ date('d-m-Y') }}" id="start_date">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="start_date_error" class="text-danger error"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 fv-plugins-icon-container">
                            <label class="form-label" for="end_date">End Date</label>
                            <input type="date" class="form-control" name="end_date" value="{{ date('d-m-Y') }}" id="end_date">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="end_date_error" class="text-danger error"></span>
                        </div>
                    </div>
                </div>
                <div class="mb-3 fv-plugins-icon-container">
                    <small class="text-light fw-semibold">Choose Working Shift Type <span class="text-danger">*</span></small>
                    <div class="form-check mt-3">
                        <input name="type" class="form-check-input" type="radio" value="regular" id="regular" checked />
                        <label class="form-check-label" for="regular"> Regular </label>
                    </div>
                    <div class="form-check">
                        <input name="type" class="form-check-input" type="radio" value="scheduled" id="scheduled" />
                        <label class="form-check-label" for="scheduled"> Scheduled </label>
                    </div>
                    <span id="type_error" class="text-danger error"></span>
                </div>
                <div class="row">
                    <label class="form-label">Set Regular Week <small>( Set week with fixed time )</small> </label>
                    <div class="col-sm-6">
                        <div class="mb-3 fv-plugins-icon-container">
                            <label class="form-label" for="start_time">Start Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="start_time" id="start_time">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="start_time_error" class="text-danger error"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 fv-plugins-icon-container">
                            <label class="form-label" for="end_time">End Time <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" name="end_time" id="end_time">
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="end_time_error" class="text-danger error"></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12">
                    <label class="form-label" for="description">Description ( <small>Optional</small> )</label>
                    <textarea class="form-control" name="description" id="description" placeholder="Enter description">{{ old('description') }}</textarea>
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
<!-- Add New Work SHift Modal -->
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
                    {data: 'type', name:'type'},
                    {data: 'start_date', name:'start_date'},
                    {data: 'start_time', name:'start_time'},
                    {data: 'end_time', name:'end_time'},
                    {data: 'status', name:'status'},
                    {data: 'action', name:'action', orderable:false, searchable:false}
                ]
            });
        });
    </script>
@endpush
