@extends('admin.layouts.app')
@section('title', $title .'-'. appName())

@section('content')
    @if(isset($url))
        <input type="hidden" id="page_url" value="{{ $url }}">
    @else
        @if(isset($data))
            <input type="hidden" id="page_url" value="{{ route('tickets.index') }}">
        @else
            <input type="hidden" id="page_url" value="{{ route('tickets.trashed') }}">
        @endif
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
                    @if(!isset($url))
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-item-center mt-3">
                                @if(empty($data))
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('tickets.index') }}" class="btn btn-success btn-primary mx-3">
                                            <span>
                                                <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">View All Records</span>
                                            </span>
                                        </a>
                                    </div>
                                @else
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('tickets.trashed') }}" class="btn btn-label-primary me-3">
                                            <span>
                                                <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                            </span>
                                        </a>
                                    </div>
                                    @can('tickets-create')
                                        <div class="dt-buttons btn-group flex-wrap">
                                            <button
                                                data-toggle="tooltip"
                                                data-placement="top"
                                                title="Add New Ticket"
                                                type="button"
                                                class="btn btn-secondary add-new btn-primary mx-3"
                                                id="add-btn"
                                                data-url="{{ route('tickets.store') }}"
                                                tabindex="0" aria-controls="DataTables_Table_0"
                                                type="button" data-bs-toggle="modal"
                                                data-bs-target="#offcanvasAddAnnouncement"
                                                >
                                                <span>
                                                    <i class="ti ti-plus me-0 me-sm-1 ti-sm"></i>
                                                    <span class="d-none d-sm-inline-block">Add New</span>
                                                </span>
                                            </button>
                                        </div>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Users List Table -->
            <div class="card">
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
                    <div class="col-md-4">
                        {{-- @if(isset($data['employees']) && !empty($data['employees']))
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                                        <option value="{{ URL::to('/team/tickets') }}">All Employees</option>
                                        @foreach ($data['employees'] as $employee)
                                            <option value="{{ URL::to('team/tickets/'.$employee->slug) }}" {{ $user->slug==$employee->slug?"selected":"" }} >
                                                {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif --}}

                        @php $url = URL::to('/team/tickets') @endphp
                        @include('admin.layouts.employee-dropdown', ['employees' => $data['employees'], 'user' => $user, 'url' => $url, 'type' => 'redirect'])
                    </div>
                </div>
                <hr />

                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No#</th>
                                        <th scope="col">Employee</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Reason</th>
                                        <th scope="col">Subject</th>
                                        <th scope="col">Created At</th>
                                        <th scope="col">Status</th>
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

    <!-- Add Ticket Modal -->
    <div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-top modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework submitBtnWithFileUpload"  data-method="post" data-modal-id="offcanvasAddAnnouncement" id="create-form" enctype="multipart/form-data" >
                        @csrf

                        <span id="edit-content">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label" for="ticket_category_id">Category <span class="text-danger">*</span></label>
                                    <select name="ticket_category_id" id="ticket_category_id" class="form-control">
                                        <option value="" selected>Select ticket category</option>
                                        @if(isset($data['ticket_categories']))
                                            @foreach ($data['ticket_categories'] as $category)
                                                <option value="{{ $category->id }}" data-category_name="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="ticket_category_id_error" class="text-danger error"></span>
                                </div>
                                <div class="col-12" id="reason-block" style="display: none">
                                    <label class="form-label" for="reason_id">Reason</label>
                                    <select name="reason_id" id="reason_id" class="form-control">
                                        <option value="" selected>Select ticket reason</option>
                                        @if(isset($data['reasons']))
                                            @foreach ($data['reasons'] as $reason)
                                                <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="reason_id_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                                    <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter subject" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="subject_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label class="form-label" for="attachment">Attachment <small>(If Any)</small></label>
                                    <input type="file" id="attachment" name="attachment" class="form-control" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="attachment_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 mt-3">
                                <label class="form-label" for="note">Discription <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter Discription here">{{ old('note') }}</textarea>

                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="note_error" class="text-danger error"></span>
                            </div>
                        </span>

                        <div class="col-12 mt-3">
                            <div class="demo-inline-spacing sub-btn">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
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
    <!-- Add Ticket Modal -->

    <div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>

                    <div class="col-12">
                        <span id="show-content"></span>
                    </div>

                    <div class="col-12 mt-3 text-end">
                        <button
                            type="reset"
                            class="btn btn-label-primary btn-reset"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function redirectPage(dropdown) {
            var selectedOption = dropdown.value;

            if (selectedOption !== '') {
                window.location.href = selectedOption;
            }
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
                    { data: 'user_id', name: 'user_id' },
                    { data: 'ticket_category_id', name: 'ticket_category_id' },
                    { data: 'reason_id', name: 'reason_id' },
                    { data: 'subject', name: 'subject' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
