@extends('admin.layouts.app')
@section('title', $title.' - '. appName())
@section('content')
@if(isset($trashed) && !empty($trashed) && $trashed == true)
<input type="hidden" id="page_url" value="{{ route('ip-managements.trashed') }}">
@else
<input type="hidden" id="page_url" value="{{ route('ip-managements.index') }}">
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
                        @if(!(Route::currentRouteName() == 'ip-managements.trashed'))
                        <div class="dt-buttons flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('ip-managements.trashed') }}" class="btn btn-label-danger mx-1">
                                <span>
                                    <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                </span>
                            </a>
                        </div>
                        @else
                        <div class="dt-buttons btn-group flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('ip-managements.index') }}" class="btn btn-success btn-primary mx-3">
                                <span>
                                    <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">View All Records</span>
                                </span>
                            </a>
                        </div>
                        @endif

                        <div class="dt-buttons btn-group flex-wrap">
                            <button data-toggle="tooltip" data-placement="top" title="Add Ip Address" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('ip-managements.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col" class="w-20">Ip Address</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Date</th>
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

<!-- Add Ip Address Modal -->
<div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                    @csrf

                    <span id="edit-content">
                        <div class="col-12 col-md-12 mb-3">
                            <label class="form-label" for="title">Ip Address <span class="text-danger">*</span></label>
                            <input type="text" id="ip_address" name="ip_address" class="form-control" placeholder="Enter Ip Address" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="ip_address_error" class="text-danger error"></span>
                        </div>




                        <div class="col-12 col-md-12 mb-3">
                            <label class="form-label" for="title">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-control select2">
                                <option value="1"> Allow</option>
                                <option value="0">Black List</option>
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="status_error" class="text-danger error"></span>
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
<!--/ Edit Ip Address Modal -->

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
                    <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script type="text/javascript">

    function selectInit(){
        setTimeout(() => {
             $('.select2').each(function () {
                $(this).select2({
                    // theme: 'bootstrap-5',
                    dropdownParent: $(this).parent(),
                });
            });
       }, 1000);
    }
    var table = $('.data_table').DataTable();
    if ($.fn.DataTable.isDataTable('.data_table')) {
        table.destroy();
    }
    $(document).ready(function() {
        var page_url = $('#page_url').val();
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: page_url + "?loaddata=yes",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ip_address',
                    name: 'ip_address'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    });
    $(document).on('click', '.restoreBtn', function() {
        var route = $(this).data('route');
        var table = $('.data_table').DataTable();
        $.ajax({
            url:route,
            method: 'GET',
            success: function(res) {
                if(res.success == true) {
                    toastr.success('You have restored IP successfully.');
                    table.draw();
                }
            }
        });
    });
</script>
@endpush
