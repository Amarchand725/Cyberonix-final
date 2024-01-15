@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@push('styles')
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-3">{{ $title }}</h5>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="row me-2">
                        <div class="col-md-2">
                            <div class="me-3">
                                <div class="dataTables_length" id="DataTables_Table_0_length">
                                    <label>
                                        {{-- <select name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" class="form-select" fdprocessedid="o5g1n8">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select> --}}
                                        @if(session()->has('message'))
                                            <div class="alert alert-success" id="message-alert">
                                                {{ session()->get('message') }}
                                            </div>
                                        @endif

                                        @if(session()->has('error'))
                                            <div class="alert alert-danger" id="message-alert">
                                                {{ session()->get('error') }}
                                            </div>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                    <label>
                                        <input type="search" class="form-control" id="search" placeholder="Search.." aria-controls="DataTables_Table_0">
                                        <input type="hidden" class="form-control" id="status" value="All">
                                    </label>
                                </div>
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('designations.trashed') }}" class="btn btn-danger btn-primary mx-3">
                                        <span>
                                            <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">All Trashed Records ( {{ $onlySoftDeleted }} )</span>
                                        </span>
                                    </a>
                                </div>
                                <div class="dt-buttons btn-group flex-wrap">
                                    <button data-toggle="tooltip" data-placement="top" title="Add New Designation" id="add-btn" data-url="{{ route('designations.store') }}" class="btn btn-success add-new btn-primary mx-3" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasAddUser" fdprocessedid="i1qq7b">
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New Designation</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="datatables-users table border-top dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                        <thead>
                            <tr>
                                <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1">S.No#</th>
                                <th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1">Name</th>
                                <th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Preview</th>
                                <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1">Description</th>
                                <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 135px;" aria-label="Actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($models as $key=>$model)
                                <tr class="odd">
                                    <td>{{ $key+1 }}.</td>
                                    <td class="sorting_1">
                                        <span class="fw-semibold">{{ $model->name??'-' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $model->class }}" text-capitalized="">{{ $model->name }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $model->description??'-' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="javascript:;" class="text-body edit-btn">
                                                <i class="ti ti-edit ti-sm me-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    <div class="row mx-2">
                                        <div class="col-sm-12 col-md-6">
                                            <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to {{ $models->count() }} of {{ $models->count() }} entries</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Employment Status Modal -->
<div class="modal fade" id="employment-status-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered1 modal-simple modal-add-new-cc">
      <div class="modal-content p-3 p-md-5">
        <div class="modal-body">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="text-center mb-4">
            <h3 class="mb-2" id="modal-title">Add Employment Status</h3>
          </div>
          <form id="addNewCCForm" class="row g-3" onsubmit="return false">
            <div class="col-12 col-md-12">
              <label class="form-label" for="name">Name</label>
              <input type="text" id="name" class="form-control" placeholder="Permanant" />
            </div>
            <div class="col-12 col-md-12">
                <label class="form-label" for="class_name">Class</label>
                <select name="class_name" class="form-control" id="class_name">
                    <option value="" selected>Select class</option>
                    <option value="purple"> Purple </option>
                    <option value="success"> Success </option>
                    <option value="info"> Info </option>
                    <option value="warning"> Warning </option>
                    <option value="primary"> Primary </option>
                    <option value="danger"> Danger </option>
                </select>
            </div>
            <div class="col-12 col-md-12">
                <div data-v-1f4ca31c="" class="note note-warning p-2 mt-3">
                    <span data-v-1f4ca31c="" class="badge badge-pill badge-purple">Terminated</span>
                    This will be the badge of the employee
                </div>
            </div>
            <div class="col-12 col-md-12">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
            </div>
            <div class="col-12 text-center">
              <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
              <button
                type="reset"
                class="btn btn-label-secondary btn-reset"
                data-bs-dismiss="modal"
                aria-label="Close"
              >
                Cancel
              </button>
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
        $(document).on('click', '.edit-btn', function(){
            $('#employment-status-modal').modal('show');
        });
    </script>
@endpush
