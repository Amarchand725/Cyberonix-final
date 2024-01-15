@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    @if(isset($employees))
        <input type="hidden" id="page_url" value="{{ route('insurances.index') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('insurances.trashed') }}">
    @endif
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header">
                                <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="d-flex justify-content-end align-item-center mt-4">
                            <div class="dt-buttons flex-wrap">
                                @if(isset($employees))
                                    @if(count($data) > 0)
                                        @can('export_insurance-create')
                                            <a data-toggle="tooltip" data-placement="top" title="Export Employees" href="{{ route('insurance.export.pdf') }}" class="btn btn-label-primary me-3">
                                                <span>
                                                    <i class="fa fa-file-pdf me-0 me-sm-1 ti-xs"></i>
                                                    <span class="d-none d-sm-inline-block">Export as PDF </span>
                                                </span>
                                            </a>
                                            <a data-toggle="tooltip" data-placement="top" title="Export Employees" href="{{ route('insurance.export.excel') }}" class="btn btn-label-success me-3">
                                                <span>
                                                    <i class="fa fa-file-excel me-0 me-sm-1 ti-xs"></i>
                                                    <span class="d-none d-sm-inline-block">Export as Excel </span>
                                                </span>
                                            </a>
                                        @endif
                                    @endif
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('insurances.trashed') }}" class="btn btn-label-danger me-1">
                                        <span>
                                            <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                        </span>
                                    </a>

                                    <div class="dt-buttons btn-group flex-wrap">
                                        <button
                                            class="btn btn-secondary add-new btn-primary mx-3"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Add Insurance"
                                            id="add-btn"
                                            data-url="{{ route('insurances.store') }}"
                                            tabindex="0" aria-controls="DataTables_Table_0"
                                            type="button" data-bs-toggle="modal"
                                            data-bs-target="#create-form-modal"
                                            >
                                            <span>
                                                <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                                <span class="d-none d-sm-inline-block">Add New</span>
                                            </span>
                                        </button>
                                    </div>
                                @else
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('insurances.index') }}" class="btn btn-success btn-primary mx-3">
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
            </div>
            <!-- Users List Table -->
            <div class="card mt-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No#</th>
                                        <th scope="col">Name As Per CNIC</th>
                                        <th scope="col" style="width:15%">CNIC</th>
                                        <th scope="col">Sex</th>
                                        <th scope="col">Date of Birth</th>
                                        <th scope="col">Marital Status</th>
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
    <div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-top modal-simple modal-add-new-cc">
            <div class="modal-content p-3 p-md-5">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-4">
                        <h3 class="mb-2" id="modal-label"></h3>
                    </div>
                    <form id="create-form" class="row g-3" data-method="" data-modal-id="create-form-modal">
                        @csrf

                        <span id="edit-content">
                            <div class="col-12 col-md-12">
                                <label class="form-label" for="user_id">Employee <span class="text-danger">*</span></label>
                                <select class="form-select select2 user_id" id="user_id" name="user_id" data-url="{{ route('employees.get_user_details') }}">
                                    <option value="" selected>Select Employee</option>
                                    @if(isset($employees))
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->first_name ?? null }} {{ $employee->last_name ?? null }} ({{ $employee->profile->employment_id ?? null }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="user_id_error" class="text-danger error"></span>
                            </div>
                            <div class="col-12 col-md-12 mt-2">
                                <label class="form-label" for="name_as_per_cnic">Name as per CNIC <span class="text-danger">*</span></label>
                                <input type="text" id="name_as_per_cnic" name="name_as_per_cnic" class="form-control" placeholder="Enter name as per cnic" />
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                <span id="name_as_per_cnic_error" class="text-danger error"></span>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label" for="date_of_birth">Date of birth <span class="text-danger">*</span></label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="date_of_birth_error" class="text-danger error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="cnic_number">CNIC Number <span class="text-danger">*</span></label>
                                    <input type="text" id="cnic_number" name="cnic_number" class="form-control cnic_number" placeholder="Enter CNIC Number here" />
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="cnic_number_error" class="text-danger error"></span>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="form-label" for="sex">Sex <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="sex" name="sex">
                                        <option value="" selected>Select Sex</option>
                                        <option value="1">Male</option>
                                        <option value="0">Female</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="sex_error" class="text-danger error"></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="marital_status">Marital Status <span class="text-danger">*</span></label>
                                    <select class="form-select select2" id="marital_status" name="marital_status">
                                        <option value="" selected>Select marital Status</option>
                                        <option value="1">Married</option>
                                        <option value="0">Single</option>
                                    </select>
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                    <span id="marital_status_error" class="text-danger error"></span>
                                </div>
                            </div>

                            <!--Relation Label-->
                            <div class="row mt-3 border-top py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Family Relations</h5>
                                    <div class="btn-wrapper">
                                        <button type="button" class="btn btn-label-primary btn-sm add-more-btn"><i class="fa fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <!--Relation and Name-->
                            <span class="relation_data">
                                <div class="row mt-2 w-full">
                                    <div class="col-md-6">
                                        <label class="form-label" for="relationships">Relationship </label>
                                        <select class="form-control relationships" id="relationships" name="relationships[]"></select>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="relationships_error" class="text-danger error"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="family_rel_names">Name </label>
                                        <input type="text" id="family_rel_names" name="family_rel_names[]" class="form-control" placeholder="Enter name" />
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="family_rel_names_error" class="text-danger error"></span>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="family_rel_dobs">Date of birth </label>
                                        <input type="date" id="family_rel_dobs" name="family_rel_dobs[]" class="form-control" />
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="family_rel_dobs_error" class="text-danger error"></span>
                                    </div>
                                   <div class="col-md-6 cnic"></div>
                                </div>
                            </span>
                            <span id="add-more-data"></span>
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
@endsection
@push('js')
    <script src="{{ asset('public/admin/assets/js/custom/insurance.js') }}"></script>
    <script>
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
                    if(response.date_of_birth != ''){
                        $('#date_of_birth').val(response.date_of_birth);
                    }else{
                        $('#date_of_birth').val('');
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
                    { data: 'name_as_per_cnic', name: 'name_as_per_cnic' },
                    { data: 'cnic_number', name: 'cnic_number' },
                    { data: 'sex', name: 'sex' },
                    { data: 'date_of_birth', name: 'date_of_birth' },
                    { data: 'marital_status', name: 'marital_status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
