@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@push('styles')
@endpush

@section('content')
<input type="hidden" id="page_url" value="{{ route('user_leaves.index') }}">

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                @if(!Auth::user()->hasRole('Admin') && Auth::user()->employeeStatus->employmentStatus->name == 'Permanent' && $remaining_filable_leaves > 0)
                    <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        <div class="dt-buttons btn-group flex-wrap">
                            <button
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Apply Leave"
                                type="button"
                                class="btn btn-secondary add-new btn-primary mx-3"
                                id="add-btn"
                                data-url="{{ route('user_leaves.store') }}"
                                tabindex="0" aria-controls="DataTables_Table_0"
                                type="button" data-bs-toggle="modal"
                                data-bs-target="#offcanvasAddAnnouncement"
                                >
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Apply Leave</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <br />
        <!-- Users List Table -->
        <div class="card input-checkbox">
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="col-md-8">
                    <span class="card-title mb-0">
                        <div class="d-flex align-items-center">
                            @if(isset($user->profile) && !empty($user->profile->profile))
                                <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$user->profile->profile, null) }}" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                            @else
                                <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
                            @endif
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="mx-3">
                                    <div class="d-flex align-items-center">
                                        <h6 class="mb-0 me-1 text-capitalize">{{ $user->first_name }} {{ $user->last_name }}</h6>
                                    </div>
                                    <small class="text-muted">
                                        @if(isset($user->jobHistory->designation->title) && !empty($user->jobHistory->designation->title))
                                            {{ $user->jobHistory->designation->title }}
                                        @else
                                            -
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Employee</th>
                                    <th style="width:250px">Date</th>
                                    <th>Days</th>
                                    <th>Behavior</th>
                                    <th>Status</th>
                                    <th>Applied At</th>
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

    <!-- Add User Leave Modal -->
    <div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-top modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework"  data-method="post" data-modal-id="offcanvasAddAnnouncement" id="create-form" enctype="multipart/form-data" >
                        @csrf

                        <input type="hidden" id="apply_leave" name="apply_leave" value="1">
                        <span id="edit-content">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label" for="leave_type_id">Leave Type <span class="text-danger">*</span></label>
                                    <select name="leave_type_id" id="leave_type_id" class="form-control">
                                        <option value="" selected>Select leave type</option>
                                        @if(isset($leave_types))
                                            @foreach ($leave_types as $leave_type)
                                                <option value="{{ $leave_type->id }}" >{{ $leave_type->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="leave_type_id_error" class="text-danger error"></span>
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
                                <label class="form-label" for="reason">Reason <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="reason" id="reason" placeholder="Enter reason here">{{ old('reason') }}</textarea>

                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="reason_error" class="text-danger error"></span>
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
    <!-- Add User Leave Modal -->

    <!-- Leave Details Modal -->
    <div class="modal fade" id="view-leave-details-modal" tabindex="-1" aria-hidden="true">
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
    <!-- Leave Details Modal -->
@endsection
@push('js')
<script>
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
                { data: 'user_id', name: 'user_id' },
                { data: 'start_at', name: 'start_at' },
                { data: 'duration', name: 'duration' },
                { data: 'behavior_type', name: 'behavior_type' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endpush
