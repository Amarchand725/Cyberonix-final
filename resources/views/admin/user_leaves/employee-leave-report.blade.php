@extends('admin.layouts.app')
@section('title', $title.' -  ' . appName())

@section('content')
    @if(empty($url))
        <input type="hidden" id="page_url" value="{{ route('employee.leaves.report') }}">
    @else
        <input type="hidden" id="page_url" value="{{ $url }}">
    @endif
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }}</h4>
                        </div>
                    </div>
                </div>
            </div>
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
                        {{-- @if(isset($employees) && !empty($employees))
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)" required>
                                        <option value="{{ route('employee.leaves.report') }}" selected>Select All</option>
                                        @foreach ($employees as $employee)
                                            <option data-user-slug="{{ $employee->slug }}" value="{{ URL::to('employee/leaves/report/'.$employee->slug) }}" {{ $user->slug==$employee->slug?"selected":"" }}>
                                                {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif --}}

                        @php $url = URL::to('employee/leaves/report') @endphp
                        @include('admin.layouts.employee-dropdown', ['employees' => $employees, 'user' => $user, 'url' => $url, 'type' => 'redirect'])
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="datatables-users table table-sm border-top dataTable no-footer dtr-column pt-4" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                                <tbody id="body">
                                    <tr class="text-center">
                                        <th class="text-truncate d-flex align-items-center text-primary fw-semibold py-3">Total Leaves</th>
                                        <td class="fw-bold">{{ $leave_report['total_leaves']??0 }}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="text-truncate d-flex align-items-center text-primary fw-semibold py-3">Leaves in Account</th>
                                        <td class="fw-bold">{{ $leave_report['total_leaves_in_account']??0 }}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="text-truncate d-flex align-items-center text-primary fw-semibold py-3">Leaves Availed</th>
                                        <td class="fw-bold">{{ $leave_report['total_used_leaves']??0 }}</td>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="text-truncate d-flex align-items-center text-primary fw-semibold py-3">Leaves in Balance</th>
                                        <td class="fw-bold">{{ $leave_report['leaves_in_balance']??0 }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="datatables-users table dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                                <thead>
                                    <tr>
                                        <th>S.No#</th>
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Leave Duration</th>
                                        <th>Leave Type</th>
                                        <th class="w-20">Reason</th>
                                        <th>Status</th>
                                        <th>Applied At</th>
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
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, },
                { data: 'start_at', name: 'start_at' },
                { data: 'user_id', name: 'user_id' },
                { data: 'duration', name: 'duration' },
                { data: 'behavior_type', name: 'behavior_type' },
                { data: 'reason', name: 'reason' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
            ]
        });
    });
</script>
@endpush
