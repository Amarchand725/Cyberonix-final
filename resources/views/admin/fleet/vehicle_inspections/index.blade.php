@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
@if(isset($data['vehicles']))
    <input type="hidden" id="page_url" value="{{ route('vehicle_inspections.index') }}">
@else
    <input type="hidden" id="page_url" value="{{ route('vehicle_inspections.trashed') }}">
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
                            @if(isset($data['vehicles']))
                                <div class="dt-buttons flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('vehicle_inspections.trashed') }}" class="btn btn-label-danger mx-1">
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
                                        title="Add Vehicle Inspection"
                                        type="button"
                                        class="btn btn-secondary add-new btn-primary mx-3"
                                        id="add-btn"
                                        data-url="{{ route('vehicle_inspections.store') }}"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" data-bs-toggle="modal"
                                        data-bs-target="#offcanvasAddAnnouncement"
                                        >
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </div>
                            @else
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('vehicle_inspections.index') }}" class="btn btn-success btn-primary mx-3">
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
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th class="w-25">Vehicle</th>
                                        <th>User</th>
                                        <th>Receive Date</th>
                                        <th>Deliver Date</th>
                                        <th>Created At</th>
                                        <th>Status</th>
                                        <th>Action</th>
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
                            <!--Vehicle User ID-->
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="vehicle">Vehicle <span class="text-danger">*</span></label>
                                <select class="form-control" id="vehicle" name="vehicle">
                                    <option value="" selected>Select Vehicle</option>
                                    @if(isset($data['vehicles']))
                                        @foreach($data['vehicles'] as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->color }}) - {{ $vehicle->registration_number }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="vehicle_error" class="text-danger error"></span>
                            </div>
                            <!--Recieve Date-->
                            <div class="col-12 col-md-12 mt-2">
                                <label class="form-label" for="receive">Recieve Date <span class="text-danger">*</span></label>
                                <input type="date" id="receive" name="receive" class="form-control" placeholder="Enter Recieve Date" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="receive_error" class="text-danger error"></span>
                            </div>
                            <!--Delivery Details-->
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <label class="form-label" for="delivery_date">Delivery Date <span class="text-danger">*</span></label>
                                    <input type="date" id="delivery_date" name="delivery_date" class="form-control" placeholder="Enter Delivery Date" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="delivery_date_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <div class="col-12 col-md-12 mt-3">
                                <label class="form-label" for="delivery_details">Delivery Details </label>
                                <textarea class="form-control" rows="5" name="delivery_details" id="delivery_details" placeholder="Enter Delivery Details"></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="delivery_details_error" class="text-danger error"></span>
                            </div>
                            <!--Inspection Details-->
                            <div class="col-12 col-md-12 mt-3">
                                <label class="form-label" for="inspection_details">Inspection Details</label>
                                <textarea class="form-control" rows="5" name="inspection_details" id="inspection_details" placeholder="Enter Inspection Detail"></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="inspection_details_error" class="text-danger error"></span>
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

    <div class="modal fade" id="history-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-top modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>

                    <div class="col-12">
                        <span id="show-content"></span>
                    </div>

                    <div class="col-12 mt-3">
                        <button
                            type="reset"
                            class="btn btn-label-secondary btn-reset"
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
                    { data: 'vehicle_id', name: 'vehicle_id' },
                    { data: 'vehicle_user_id', name: 'vehicle_user_id' },
                    { data: 'receive_date', name: 'receive_date' },
                    { data: 'delivery_date', name: 'delivery_date' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
