@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    @if(isset($temp))
        <input type="hidden" id="page_url" value="{{ route('vehicle_users.all_users') }}">
    @elseif(isset($data['available_vehicles']))
        <input type="hidden" id="page_url" value="{{ route('vehicle_users.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('vehicle_users.trashed') }}">
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
                    @if(isset($temp))
                        <div class="col-md-6">
                        <div class="d-flex justify-content-end align-item-center mt-4">
                            @if(isset($data['available_vehicles']))
                                @if(!isset($temp)))
                                    <div class="dt-buttons flex-wrap">
                                        <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('vehicle_users.trashed') }}" class="btn btn-label-danger mx-1">
                                            <span>
                                                <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                            </span>
                                        </a>
                                    </div>
                                @endif
                                <div class="dt-buttons btn-group flex-wrap">
                                    <button
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Add Vehicle User"
                                        type="button"
                                        class="btn btn-secondary add-new btn-primary mx-3"
                                        id="add-btn"
                                        data-url="{{ route('vehicle_users.store') }}"
                                        tabindex="0" aria-controls="DataTables_Table_0"
                                        type="button" 
                                        data-bs-toggle="modal"
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
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('vehicle_users.index') }}" class="btn btn-success btn-primary mx-3">
                                        <span>
                                            <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All Records</span>
                                        </span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width:5%">S.No#</th>
                                        <th scope="col">User</th>
                                        <th scope="col" style="width:25%">Vehicle</th>
                                        <th scope="col">Deliver Date</th>
                                        <th scope="col">End Date</th>
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

    <!-- Add Employment Status Modal -->
    <div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-top modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                        @csrf

                        <span id="edit-content">
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="user">User <span class="text-danger">*</span></label>
                                <select name="user" id="user" class="form-select select2 user_id" data-url="{{ route('employees.get_user_details') }}">
                                    <option value="" selected>Select User</option>
                                    @if(isset($data['users']))
                                        @foreach ($data['users'] as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="vehicle_error" class="text-danger error"></span>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="user_cnic">User CNIC <span class="text-danger">*</span></label>
                                <input type="text" name="user_cnic" id="user_cnic" class="form-control cnic_number" placeholder="Enter User CNIC" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="user_cnic_error" class="text-danger error"></span>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="vehicle">Vehicle <span class="text-danger">*</span></label>
                                <select name="vehicle" id="vehicle" class="form-select select2">
                                    <option value="" selected>Select Vehicle</option>
                                    @if(isset($data['available_vehicles']))
                                        @foreach ($data['available_vehicles'] as $vehicle)
                                            <option value="{{ $vehicle->id }}">{{ $vehicle->name }} ({{ $vehicle->color }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="vehicle_error" class="text-danger error"></span>
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label" for="deliver">Deliver <span class="text-danger">*</span></label>
                                <input type="date" id="deliver" name="deliver" class="form-control" placeholder="Enter deliver" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="deliver_error" class="text-danger error"></span>
                            </div>

                            <div class="col-12 col-md-12 mt-2">
                                <label class="form-label" for="note">Note</label>
                                <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note here...."></textarea>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="note_error" class="text-danger error"></span>
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

    <!-- Vehicle User Details -->
    <div class="modal fade" id="details-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered1 modal-simple modal-add-new-cc">
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
    <!-- Vehicle User Details -->

    <!-- Share Vehicle -->
    <div class="modal fade" id="share-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-top modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form id="create-form" class="row g-3" data-method="" data-modal-id="share-modal">
                        @csrf

                        <input type="hidden" id="vehicle_user_id" name="vehicle_user_id" value="" />
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="user">User <span class="text-danger">*</span></label>
                            <select name="user" id="user_id" class="form-control user_id" data-url="{{ route('employees.get_user_details') }}">
                                <option value="" selected>Select User</option>
                                @if(isset($data['users']))
                                    @foreach ($data['users'] as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="user_error" class="text-danger error"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="user_cnic">User CNIC <span class="text-danger">*</span></label>
                            <input type="text" name="user_cnic" id="user_cnic" class="form-control cnic_number" placeholder="Enter User CNIC" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="user_cnic_error" class="text-danger error"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="deliver_date">Deliver <span class="text-danger">*</span></label>
                            <input type="date" id="deliver_date" name="deliver_date" class="form-control" placeholder="Enter deliver" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="deliver_date_error" class="text-danger error"></span>
                        </div>

                        <div class="col-12 col-md-12 mt-2">
                            <label class="form-label" for="note">Note</label>
                            <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note here...."></textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="note_error" class="text-danger error"></span>
                        </div>

                        <div class="col-12 mt-2 action-btn">
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
    <!-- Share Vehicle -->
@endsection
@push('js')
    <script>
    $(document).ready(function(){
         $('select').each(function() {
                $(this).select2({ dropdownParent: $(this).parent()});
            });
    });
    $(document).on('click', '.edit-btn', function(){
         setTimeout(() => {
            
        // $('#user').select2({});
        $('select').each(function() { 
                $(this).select2({ dropdownParent: $(this).parent()});
            });
           }, 800);
        //  $('select').each(function() { 
        //      console.log($(this));
        //         $(this).select2({ dropdownParent: $(this).parent()});
        //     });
    });
        $(document).on('click', '.shareBtn', function(){
            var vehicle_user_id = $(this).attr('data-vehicle-user-id');
            $('#vehicle_user_id').val(vehicle_user_id);
            
            var targeted_modal = $(this).attr('data-bs-target');
    
            //reset
            $(targeted_modal).find('#create-form input[type="text"], #create-form textarea').val('');
            $(targeted_modal).find('#create-form input[type="number"]').val('');
            $(targeted_modal).find('#create-form input[type="date"]').val('');
            $(targeted_modal).find('#create-form input[type="email"]').val('');
            $(targeted_modal).find('#create-form input[type="time"]').val('');
            $(targeted_modal).find('#create-form select').val('');
            $(targeted_modal).find('#create-form input[type="checkbox"], #create-form input[type="radio"]').prop('checked', false);
        
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.description) {
                CKEDITOR.instances.description.setData('');
            }
            //reset
        
            var url = $(this).attr('data-url');
            var modal_label = $(this).attr('title');
        
            $(targeted_modal).find('#modal-label').html(modal_label);
            $(targeted_modal).find("#create-form").attr("action", url);
            $(targeted_modal).find("#create-form").attr("data-method", 'POST');
            // if ($('select').data('select2')) {
            //     $('select').select2('destroy');
            // }
            
          
        });

        $('.user_id').on('change', function(){
            
            var url = $(this).attr('data-url');
            var user_id = $(this).val();
            $.ajax({
                url: url,
                type: 'GET',
                data:{user_id:user_id},
                success: function(response) {
                    if(response.cnic != ''){
                        $('.cnic_number').val(response.cnic);
                    }else{
                        $('.cnic_number').val('');
                    }
                }
            });
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
                    { data: 'user_id', name: 'user_id' },
                    { data: 'vehicle_id', name: 'vehicle_id' },
                    { data: 'deliver_date', name: 'deliver_date' },
                    { data: 'end_date', name: 'end_date' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
