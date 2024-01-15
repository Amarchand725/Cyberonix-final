@extends('admin.layouts.app')
@section('title', $title .' -  ' . appName())

@section('content')
@if(empty($url))
    <input type="hidden" id="page_url" value="{{ route('employee.attendance.daily-log') }}">
@else
    <input type="hidden" id="page_url" value="{{ $url }}">
@endif
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Users List Table -->
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
                        <a href="{{ route('employees.show', $user->slug) }}" class="text-body text-truncate">
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
                        </a>
                    </span>
                </div>
                <div class="col-md-4">
                    @php $url = URL::to('employee/attendance/daily-log') @endphp
                    @include('admin.layouts.employee-dropdown', ['employees' => $employees, 'user' => $user, 'url' => $url, 'month' => $month, 'year' => $year, 'type' => 'redirect'])
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="datatables-users table border-top dataTable no-footer dtr-column data_table" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 1227px;">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
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
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_id', name: 'user_id' },
                    { data: 'in_date', name: 'in_date' },
                    { data: 'time', name: 'time' },
                    { data: 'behavior', name: 'behavior' },
                ]
            });
        });
    </script>
@endpush
