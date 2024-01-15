@extends('admin.layouts.app')
@section('title', $title.'-' . appName())

@section('content')
<input type="hidden" id="page_url" value="{{ route('roles.getRoleEmployees') }}">
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header">
                    <h4 class="fw-bold pb-4 border-bottom"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                    <p class="mt-2 mb-0">
                         A role provided access to predefined menus and features so that depending on <br /> assigned role an administrator can have access to what user needs.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        @foreach ($roles as $role)
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-normal mb-2">Total <span class="">{{ count($role->users) }}</span> users</h6>
                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                @php $counter = 0; @endphp
                                @foreach ($role->users as $role_user)
                                    @if($counter <=5)
                                        @php $counter++; @endphp
                                        <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{$role_user->first_name }} {{$role_user->last_name }}" class="avatar avatar-sm pull-up">
                                            @if(isset($role_user->profile) && !empty($role_user->profile->profile))
                                                <img class="rounded-circle" src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$role_user->profile->profile, null) }}" alt="Avatar" />
                                            @else
                                                <img class="rounded-circle" src="{{ asset('public/admin/assets/img/avatars/default.png') }}" alt="Avatar" />
                                            @endif
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        <div class="d-flex justify-content-between align-items-end mt-1">
                            <div class="role-heading">
                                <h4 class="mb-1">{{ $role->name }}</h4>
                                <a href="javascript:;"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Edit Record"
                                    data-edit-url="{{ route('roles.edit', $role->id) }}"
                                    data-url="{{ route('roles.update', $role->id) }}"
                                    class="role-edit-modal edit-btn"
                                    tabindex="0" aria-controls="DataTables_Table_0"
                                    type="button" data-bs-toggle="modal"
                                    data-bs-target="#addRoleModal">
                                    <span><i class="fa fa-edit"></i> Edit Role</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="row h-100">
                    <div class="col-sm-5">
                        <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                            <img src="{{ asset('public/admin') }}/assets/img/illustrations/add-new-roles.png" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83" />
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <div class="card-body text-sm-end text-center ps-sm-0">
                            <button
                                id="add-btn"
                                data-toggle="tooltip" data-placement="top" title="Add Role"
                                data-url="{{ route('roles.store') }}"
                                class="btn add-new btn-primary mb-md-0 mx-3"
                                tabindex="0" aria-controls="DataTables_Table_0"
                                type="button" data-bs-toggle="modal"
                                data-bs-target="#addRoleModal">
                                <span>Add Role</span>
                            </button>
                            <p class="mb-0 mt-1">Add role, if it does not exist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="me-3">
                                    <div class="dataTables_length" id="DataTables_Table_0_length">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mt-3">
                                    <div class="dt-buttons btn-group flex-wrap">
                                        <button
                                            class="btn btn-secondary add-new btn-primary mx-3"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Add New Employee"
                                            id="add-btn"
                                            data-url="{{ route('employees.store') }}"
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
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column data_table">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No#</th>
                                        <th scope="col">Employee</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Department</th>
                                        <th scope="col">Employee Shift</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="body"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Add Role Modal -->
                <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered modal-add-new-role">
                        <div class="modal-content p-3 p-md-5">
                            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="modal-body">
                                <div class="text-center mb-4">
                                    <h3 class="role-title mb-2" id="modal-label">Add New Role</h3>
                                    <p class="text-muted">Set role permissions</p>
                                </div>
                                <!-- Add role form -->
                                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework" data-method="" data-modal-id="addRoleModal" id="create-form">
                                    @csrf

                                    <span id="edit-content">
                                        <div class="col-12 mb-4">
                                            <label class="form-label" for="name">Role Name <span class="text-danger">*</span></label>
                                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter a role name" tabindex="-1" />
                                            <span id="name_error" class="text-danger"></span>
                                        </div>
                                        <div class="col-12">
                                            <h5>Role Permissions</h5>
                                            <!-- Permission table -->
                                            <div class="table-responsive">
                                                <table class="table table-flush-spacing">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-nowrap fw-semibold">
                                                                Administrator Access
                                                                <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                                            </td>
                                                            <td>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="selectAll" />
                                                                    <label class="form-check-label" for="selectAll"> Select All </label>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @foreach ($models as $permission)
                                                            <tr>
                                                                <td class="text-nowrap fw-semibold">{{ ucfirst($permission->label) }} Management</td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        @foreach (SubPermissions($permission->label) as $sub_permission)
                                                                            @php $label = explode('-', $sub_permission->name) @endphp
                                                                            <div class="form-check me-3 me-lg-5">
                                                                                <input class="form-check-input" name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
                                                                                <label class="form-check-label" for="userManagementRead-{{ $sub_permission->id }}"> {{ Str::ucfirst($label[1]) }}</label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!-- Permission table -->
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
                <!--/ Add Role Modal -->

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
                                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework"
                                    data-method="" data-modal-id="create-form-modal" id="create-form">
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
                                        <div class="mb-3">
                                            <label class="d-block form-label">Gender <span class="text-danger">*</span></label>
                                            <div class="form-check mb-2">
                                              <input
                                                type="radio"
                                                id="gender-male"
                                                name="gender"
                                                class="form-check-input"
                                                checked
                                                required
                                                value="male"
                                              />
                                              <label class="form-check-label" for="gender-male">Male</label>
                                            </div>
                                            <div class="form-check">
                                              <input
                                                type="radio"
                                                id="gender-female"
                                                name="gender"
                                                class="form-check-input"
                                                required
                                                value="female"
                                              />
                                              <label class="form-check-label" for="gender-female">Female</label>
                                            </div>
                                            <div class="fv-plugins-message-container invalid-feedback"></div>
                                            <span id="gender_error" class="text-danger error"></span>
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
                                            <div class="mb-3 col-6">
                                                <label class="form-label" for="salary">Salary</label>
                                                <input type="number" id="salary" name="salary" class="form-control" placeholder="Enter salary">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="salary_error" class="text-danger error"></span>
                                            </div>
                                            <div class="mb-3 col-6">
                                                <label class="form-label" for="multicol-birthdate">Joining Date <span class="text-danger">*</span></label>
                                                <input
                                                type="date"
                                                id="multicol-birthdate"
                                                name="joining_date"
                                                class="form-control dob-picker"
                                                placeholder="YYYY-MM-DD" />
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
                                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework"  data-method="post" data-modal-id="terminate-employee-modal" id="create-form" enctype="multipart/form-data" >
                                    @csrf
                                    <input type="hidden" name="user_id" id="terminate_user_id" value="">
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
                                                <label class="form-label" for="notice_period">Notice Period </label>
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
                                            <label class="form-label" for="reason_for_resignation">Reason for termination <span class="text-danger">*</span></label>
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
    <script>
        $("#selectAll").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    </script>
    <script src="{{ asset('public/admin/assets/js/custom/employee.js') }}"></script>

    <script>
        //datatable
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        $.fn.dataTable.ext.errMode = 'throw';
        $(document).ready(function() {
            var page_url = $('#page_url').val();
            var table = $('.data_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: page_url+"?loaddata=yes",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'first_name', name: 'first_name' },
                    { data: 'role', name: 'role' },
                    { data: 'Department', name: 'Department' },
                    { data: 'shift', name: 'shift' },
                    { data: 'emp_status', name: 'emp_status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
