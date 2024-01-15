<input type="hidden" name="role_id" id="role_id" value="{{ $role->id }}" >
<div class="col-12 mb-4">
    <label class="form-label" for="name">Role Name <span class="text-danger">*</span></label>
    <input type="text" id="name" name="name" class="form-control" value="{{ $role->name }}" placeholder="Enter a role name" tabindex="-1" />
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
                                        @php $bool = false @endphp
                                        @foreach ($role_permissions as $role_permission)
                                            @if($role_permission==$sub_permission->name)
                                                @php $bool = true; @endphp
                                            @endif
                                        @endforeach
                                        @if($bool)
                                            <input class="form-check-input" checked name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
                                        @else
                                            <input class="form-check-input" name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
                                        @endif
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

<script>
    $("#selectAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
<script src="{{ asset('public/admin/assets/js/custom/employee.js') }}"></script>
