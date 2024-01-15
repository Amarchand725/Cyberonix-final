<div class="col-12 mb-4">
    <div class="d-flex align-items-center mb-4 border-bottom">
        @if(isset($user->profile) && !empty($user->profile->profile))
            <img src="{{ resize(asset('public/admin/assets/img/avatars').'/'.$user->profile->profile, null) }}" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
        @else
            <img src="{{ asset('public/admin') }}/default.png" style="width:40px !important; height:40px !important" alt class="h-auto rounded-circle" />
        @endif
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
            <div class="mx-3">
                <div class="d-flex align-items-center">
                    <h6 class="mb-0 me-1">{{ $user->first_name }}</h6>
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
    <input type="hidden" name="user_slug" value="{{ $user->slug }}">
</div>
<div class="col-12">
    <h5>User Permissions</h5>
    <!-- Permission table -->
    <div class="table-responsive">
        <table class="table table-flush-spacing">
            <tbody class="input-checkbox">
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
                                        @foreach ($user_permissions as $user_permission)
                                            @if($user_permission==$sub_permission->name)
                                                @php $bool = true; @endphp
                                            @endif
                                        @endforeach
                                        @if($bool)
                                            <input class="form-check-input checkbox" checked name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
                                        @else
                                            <input class="form-check-input checkbox" name="permissions[]" value="{{ $sub_permission->id }}" type="checkbox" id="userManagementRead-{{ $sub_permission->id }}" />
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
    // $("#selectAll").click(function () {
    //     $('input:checkbox').not(this).prop('checked', this.checked);
    // });

    $(document).ready(function() {
        $(document).on('click', '#selectAll', function() {
            $(this).parents('.input-checkbox').find(".checkbox").prop("checked", $(this).prop("checked"));
        });

        $(document).on('click', ".checkbox", function() {
            $(this).parents('.input-checkbox').find(".checkbox").length;
        });
    });
</script>
@push('js')
    <script src="{{ asset('public/admin/assets/js/custom/employee.js') }}"></script>
@endpush
