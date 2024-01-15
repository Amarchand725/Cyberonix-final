@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@push('styles')
@endpush

@section('content')
@if(request()->route()->getName() != 'new_joinings')
    @if(isset($trashed) && !empty($trashed) && $trashed == true)
        <input type="hidden" id="page_url" value="{{ route('employees.trashed') }}">
    @else
        <input type="hidden" id="page_url" value="{{ route('employees.index') }}">
    @endif
@else
    <input type="hidden" id="page_url" value="{{ route('new_joinings') }}">
@endif

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    </div>
                </div>
                @if(request()->route()->getName() != 'new_joinings')
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end align-item-center mt-4">
                            @if(isset($trashed) && !empty($trashed) && $trashed == true)
                                <div class="dt-buttons btn-group flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('employees.index') }}" class="btn btn-success btn-primary mx-3">
                                        <span>
                                            <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">View All Records</span>
                                        </span>
                                    </a>
                                </div>
                            @else
                                <div class="dt-buttons flex-wrap">
                                    <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('employees.trashed') }}" class="btn btn-label-danger me-1">
                                        <span>
                                            <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                        </span>
                                    </a>
                                </div>
                                <div class="dt-buttons btn-group flex-wrap">
                                    <button class="btn btn-secondary add-new btn-primary mx-3" data-toggle="tooltip" data-placement="top" title="Add New Employee" id="add-btn" data-url="{{ route('employees.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#create-form-modal">
                                        <span>
                                            <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                            <span class="d-none d-sm-inline-block">Add New</span>
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
            <div class="row p-3">
                <div class="col-md-4 mb-3">
                    <label for="">Department</label>
                    <select name="department" id="department" class="select2 form-select department">
                        <option value="all">All</option>
                        @if(!empty(getAllDepartments()))
                        @foreach(getAllDepartments() as $department)
                        <option value="{{$department->id ?? ''}}">{{$department->name ?? ''}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="">Shift</label>
                    <select name="shift" id="shift" class="select2 form-select shift">
                        <option value="all">All</option>
                        @if(!empty(getWorkShifts()))
                        @foreach(getWorkShifts() as $shift)
                        <option value="{{$shift->id ?? ''}}">{{$shift->name ?? ''}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="">Employment</label>
                    <select name="emp_status" id="emp_status" class="select2 form-select emp_status">
                        <option value="all">All</option>
                        @if(!empty(getEmploymentStatus()))
                        @foreach(getEmploymentStatus() as $employmentStatus)
                        <option value="{{$employmentStatus->id ?? ''}}">{{$employmentStatus->name ?? ''}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>

            </div>
        </div>

        <!-- Users List Table -->
        <div class="card mt-4">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table table-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No#</th>
                                    <th>Employee</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th style="width:200px">Shift</th>
                                    <th>Employment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Add New Employee Modal -->
            <div class="modal fade" id="create-form-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="role-title mb-2" id="modal-label"></h3>
                            </div>
                            <!-- Add role form -->
                            <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="create-form-modal" id="create-form">
                                @csrf

                                <span id="edit-content">
                                    <div class="row">
                                        <div class="mb-3 fv-plugins-icon-container col-6">
                                            <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="first_name" placeholder="Enter first name" name="first_name">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="first_name_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 fv-plugins-icon-container col-6">
                                            <label class="form-label" for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" placeholder="Enter last name" name="last_name">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="last_name_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 fv-plugins-icon-container col-6">
                                            <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                                            <input type="email" id="email" class="form-control" placeholder="john.doe@example.org" aria-label="john.doe@example.org" name="email">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="email_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 fv-plugins-icon-container col-6">
                                            <label class="form-label" for="phone_number">Mobile</label>
                                            <input type="text" id="phone_number" class="form-control mobileNumber" placeholder="Enter phone number" name="phone_number">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="phone_number_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 fv-plugins-icon-container col-6">
                                            <label class="d-block form-label">Gender <span class="text-danger">*</span></label>
                                            <div class="form-check mb-2">
                                                <input type="radio" id="gender-male" name="gender" class="form-check-input" checked required value="male" />
                                                <label class="form-check-label" for="gender-male">Male</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="gender-female" name="gender" class="form-check-input" required value="female" />
                                                <label class="form-check-label" for="gender-female">Female</label>
                                            </div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="gender_error" class="text-danger error"></span>
                                        </div>
                                        {{-- <div class="mb-3 fv-plugins-icon-container col-4">
                                            <label class="d-block form-label">Generate Email on server <span class="text-danger">*</span></label>
                                            <div class="form-check mb-2">
                                                <input type="radio" id="generate-email-yes" name="generate_email" class="form-check-input" checked required value="1" />
                                                <label class="form-check-label" for="generate-email-yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="generate-email-no" name="generate_email" class="form-check-input" required value="2" />
                                                <label class="form-check-label" for="generate-email-no">No</label>
                                            </div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="generate_email_error" class="text-danger error"></span>
                                        </div> --}}
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-6">
                                            <label class="form-label" for="employment_id">Employee ID</label>
                                            <input type="text" id="employment_id" name="employment_id" class="form-control phone-mask" placeholder="Enter employment id">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="employment_id_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label class="form-label" for="employment_status_id">Employment Status <span class="text-danger">*</span></label>
                                            <select id="employment_status_id" name="employment_status_id" class="form-select select2">
                                                <option value="" selected>Select Status</option>
                                                @if(isset($employment_statues))
                                                @foreach ($employment_statues as $employment_status)
                                                <option value="{{ $employment_status->id }}">{{ $employment_status->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="employment_status_id_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-6">
                                            <label class="form-label" for="role_ids">Role <span class="text-danger">*</span></label>
                                            <select id="role_ids" name="role_ids[]" multiple class="form-select select2">
                                                @if(isset($roles))
                                                @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="role_ids_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label class="form-label" for="designation_id">Designation <span class="text-danger">*</span></label>
                                            <select id="designation_id" name="designation_id" class="form-select select2">
                                                <option value="" selected>Select designation</option>
                                                @if(isset($designations))
                                                @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}">{{ $designation->title }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="designation_id_error" class="text-danger error"></span>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="mb-3 col-6">
                                            <label class="form-label" for="department_id">Departments <span class="text-danger">*</span></label>
                                            <select id="department_id" name="department_id" class="form-select select2">
                                                <option value="" selected>Select department</option>
                                                @if(isset($departments))
                                                @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="department_id_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label class="form-label" for="work_shift_id">Work Shift <span class="text-danger">*</span></label>
                                            <div class="position-relative">
                                                <select id="work_shift_id" name="work_shift_id" class="form-select select2">
                                                    <option value="">Select work shift</option>
                                                    @if(isset($work_shifts))
                                                    @foreach ($work_shifts as $work_shift)
                                                    <option value="{{ $work_shift->id }}">{{ $work_shift->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                                <span id="work_shift_id_error" class="text-danger error"></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="salary">Currency</label>
                                            <select name="currency" id="currency" class="form-control">
                                                <option value="">Select</option>
                                                @if(!empty(currencyList()))
                                                @foreach(currencyList() as $currency)
                                                <option value="{{$currency->code ?? ''}}">{{$currency->title ?? '-'}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" name="conversion_amount_hidden" class="conversion_amount_hidden">
                                            <input type="hidden" name="conversion_rate" class="conversion_rate">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="currency_error" class="text-danger error"></span>
                                            <span class="currency_rate_after_conversion" style="font-size: 13px;opacity: .7;"></span>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="salary">Salary</label>
                                            <input type="number" id="salary" name="salary" class="form-control" placeholder="Enter salary">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="salary_error" class="text-danger error"></span>
                                        </div>
                                        <div class="mb-3 col-4">
                                            <label class="form-label" for="multicol-birthdate">Joining Date <span class="text-danger">*</span></label>
                                            <input type="date" id="multicol-birthdate" name="joining_date" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="joining_date_error" class="text-danger error"></span>
                                        </div>
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
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Add New Employee Modal -->

            <!-- Add Salary Modal -->
            <div class="modal fade" id="promote-employee-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-top modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="role-title mb-2" id="salary-title-label"></h3>
                            </div>
                            <!-- Add role form -->
                            <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="promote-employee-modal" id="promote-employee-form">
                                @csrf

                                <span id="promote-content"></span>

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
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add Salary Modal -->

            <!-- Add Direct Permission Modal -->
            <div class="modal fade" id="edit-direct-permission-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-5">
                        <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <h3 class="role-title mb-2" id="modal-label"></h3>
                                <p class="text-muted">Set user permissions</p>
                            </div>
                            <!-- Add role form -->
                            <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="edit-direct-permission-modal" id="create-form">
                                @csrf

                                <span id="edit-content"></span>

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
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add Role Modal -->

            <!-- Terminate Employee -->
            <div class="modal fade" id="terminate-employee-modal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-top modal-simple modal-add-new-cc">
                    <div class="modal-content p-3 p-md-5">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="mb-2" id="modal-label"></h3>
                            </div>
                            <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="post" action="{{ route('resignations.store') }}" data-modal-id="terminate-employee-modal" id="create-form" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" id="terminate_user_id" value="">
                                <input type="hidden" name="from" value="termination" />
                                <span id="edit-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <label class="form-label" for="employment_status">Employment Status <span class="text-danger">*</span></label>
                                            <select name="employment_status" id="employment_status" class="form-control">
                                                <option value="" selected>Select type</option>
                                                @if(isset($termination_employment_statues))
                                                @foreach($termination_employment_statues as $emp_status)
                                                <option value="{{ $emp_status->id }}">{{ $emp_status->name }} </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="employment_status_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label class="form-label" for="notice_period">Notice Period <span class="text-danger">*</span></label>
                                            <select name="notice_period" id="notice_period" class="form-control">
                                                <option value="" selected>Select notice period</option>
                                                <option value="Immediately">Immediately </option>
                                                <option value="One Week">One Week </option>
                                                <option value="One Month">One Month </option>
                                            </select>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="notice_period_error" class="text-danger error"></span>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <label class="form-label" for="resignation_date">Terminate Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="resignation_date" name="resignation_date">
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="resignation_date_error" class="text-danger error"></span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-12 mt-3">
                                        <label class="form-label" for="reason_for_resignation">Reason for termination </label>
                                        <textarea class="form-control" rows="5" name="reason_for_resignation" id="reason_for_resignation" placeholder="Enter reason for termination here">{{ old('reason_for_resignation') }}</textarea>
                                        <div class="fv-plugins-message-container invalid-feedback"></div>
                                        <span id="reason_for_resignation_error" class="text-danger error"></span>
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
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('public/admin/assets/js/custom/employee.js') }}"></script>
<script>
    function selectInit() {
        setTimeout(() => {
            $('select').each(function() {
                $(this).select2({
                    // theme: 'bootstrap-5',
                    dropdownParent: $(this).parent(),
                });
            });
        }, 1000);
    }

    $(document).ready(function() {
        loadPageData()

        $('.terminate-emp').click(function() {
            console.log(this);
        });
        setTimeout(() => {
            $('select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        });

        $(document).on('click', '#terminate-employee', function() {
            var employee_id = $(this).attr('data-user-id');
            $('#terminate_user_id').val(employee_id);
        });
        $(document).on('click', '.add-new', function() {
            $('.form-select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        });

        $('#create-form-modal').on('shown.bs.modal', function() {
            // Initialize select2 for all "select2" elements within the modal
            $('select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        });

        $('#create-form-modal').on('shown.bs.modal', function() {
            // Initialize select2 for all "select2" elements within the modal
            $('select2').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        });
        $('.terminate-emp').click(function() {
            console.log(this);
        });
        setTimeout(() => {
            $('select').each(function() {
                $(this).select2({
                    dropdownParent: $(this).parent(),
                });
            });
        }, 800);

    });
    $(document).on("change", ".department", function() {
        var id = $(this).val();
        loadPageData()
    });
    $(document).on("change", ".shift", function() {
        var id = $(this).val();
        loadPageData()
    });
    $(document).on("change", ".emp_status", function() {
        var id = $(this).val();
        loadPageData()
    });
    //datatable
    function loadPageData() {
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        $.fn.dataTable.ext.errMode = 'throw';
        var page_url = $('#page_url').val();
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: page_url + "?loaddata=yes",
                type: "GET",
                data: function(d) {
                    d.emp_status = $('.emp_status').val();
                    d.department = $('.department').val();
                    d.shift = $('.shift').val();
                    d.search = $('input[type="search"]').val();
                },
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(error);
                    console.log(code);
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'Department',
                    name: 'Department'
                },
                {
                    data: 'shift',
                    name: 'shift'
                },
                {
                    data: 'emp_status',
                    name: 'emp_status'
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });
    }
    //datatable


    // for employee create and edit modal  currency rate on change
    $(document).on("change", "#currency", function() {
        var code = $(this).val();
        var salary = $("#salary").val();
        if (code && code != "PKR") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }

    });
    $(document).on("keyup", "#salary", function() {
        var code = $("#currency").val();
        var salary = $(this).val();
        if (code && code != "PKR") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }
    });
    // for employee create and edit modal  currency rate on change



    // for promotion modal  currency rate on change
    $(document).on("change", "#currency_promotion", function() {
        var code = $(this).val();
        var salary = $("#raise_salary").val();
        if (code && code != "PKR") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }

    });
    $(document).on("keyup", "#raise_salary", function() {
        var code = $("#currency_promotion").val();
        var salary = $(this).val();
        if (code && code != "PKR") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }
    });

    // for promotion modal


    function convertCurrency(code, salary) {
        console.log(code)
        if (code && salary) {
            $.ajax({
                url: "{{ route('employees.getCurrencyRate') }}",
                method: "GET",
                data: {
                    code: code,
                    salary: salary,
                },
                beforeSend: function() {
                    $(".currency_rate_after_conversion").html('<div class="spinner-grow" style="width: 12px;height: 12px !important;color: black;" role="status"><span class="sr-only">Loading...</span></div>')
                },
                success: function(res) {
                    if (res.success == false) {
                        $(".currency_rate_after_conversion").html("");
                    }
                    if (res.success == true) {

                        $(".conversion_amount_hidden").val(res.data.convertedAmount);
                        $(".conversion_rate").val(res.data.conversionRate)
                        $(".currency_rate_after_conversion").html("After Conversion: " + res.data.convertedAmountWithSymbol)
                    }
                },
                error: function(xhr, status, error) {
                    $(".currency_rate_after_conversion").html("");
                    console.log(error)
                    console.log(xhr)
                }
            });
        }
    }
</script>

@endpush
