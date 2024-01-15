@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@section('content')
@if(empty($url))
<input type="hidden" id="page_url" value="{{ route('get.mark.attendance.by.admin') }}">
@else
<input type="hidden" id="page_url" value="{{ $url }}">
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
            </div>
        </div>

        <!-- Users List Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center row">
                <div class="row">
                    <div class="col-md-4">
                        <label>Date Range</label>
                        <input type="text" class="form-control w-100" placeholder="YYYY-MM-DD to YYYY-MM-DD" id="flatpickr-range" value="" />
                        <span class="text-danger" id="date_error"></span>
                    </div>

                    <div class="col-md-4">
                        <label>Behavior</label>
                        <select class="select2 form-select" id="filter_behavior" name="behavior">
                            <option value="fullDay">Full Day</option>
                            <option value="halfDay">Half Day</option>
                            <option value="absent">Absent</option>
                            <option value="lateIn">Late In</option>
                            <option value="earlyOut">Early Out</option>
                        </select>
                        <span class="text-danger" id="behavior_error"></span>
                    </div>

                    <div class="col-md-4">
                        <label></label>
                        <button type="button" disabled id="process" class="btn btn-primary d-none w-100" style="display:none">Processing...</button>
                        <button type="button" id="filter-btn" class="btn btn-primary  d-block w-100"><i class="fa fa-attendance me-2"></i> Attendance Marked </button>
                    </div>
                </div>
            </div>
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                            <thead>
                                <tr>
                                    <th>
                                        <div>
                                            <input class="form-check-input select-all" type="checkbox" />
                                        </div>
                                        <span class="text-danger" id="selectUser_error"></span>
                                    </th>
                                    <th scope="col">Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div>
                                            <input class="form-check-input checkbox" type="checkbox" data-type="" value="{{ $user->id??'-' }}" />
                                        </div>
                                    </td>
                                    <th>
                                        {{$user->first_name??'-'}} {{$user->last_name??'-'}}
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(".select-all").click(function() {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>

<script>
    $('#filter-btn').on('click', function() {

        var dates = $('#flatpickr-range').val();
        var behavior = $('#filter_behavior').val();
        var selectedUsers = [];

        var checkedCheckboxes = $('.checkbox:checked');
        if (checkedCheckboxes.length === 1) {
            selectedUsers.push(checkedCheckboxes.val());
        } else if (checkedCheckboxes.length > 1) {
            checkedCheckboxes.each(function() {
                selectedUsers.push($(this).val()); // Add checked values to the array
            });
        }

        var valid = true;

        if (dates == '') {
            valid = false;
            $('#date_error').text('Please Select the date');
        } else {
            $('#date_error').text('');
        }

        if (behavior == '') {
            valid = false;
            $('#behavior_error').text('Please Select the behavior');
        } else {
            $('#behavior_error').text('');
        }

        if (selectedUsers == '') {
            valid = false;
            $('#selectUser_error').text('Please Select the User');
        } else {
            $('#selectUser_error').text('');
        }

        if (valid) {
            $.ajax({
                url: '{{ route("mark.attendance.by.admin") }}',
                method: 'POST',
                data: {
                    dates: dates,
                    behavior: behavior,
                    selectedUsers: selectedUsers
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Ensure CSRF token is included
                },
                success: function(response) {
                    if (response.success) {
                        $('#flatpickr-range').val('');
                        $('#filter_behavior').val('');
                        $('input:checkbox').not(this).prop('checked', this.checked);
                        toastr.success(response.message)
                        location.reload
                        // window.location.href = response.route;

                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    });
</script>
@endpush