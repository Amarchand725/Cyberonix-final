@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@section('content')
    @if(empty($url))
        <input type="hidden" id="page_url" value="{{ route('mark_attendance.index') }}">
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
                        {{-- @if(isset($data['employees']) && !empty($data['employees']))
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <select class="select2 form-select" id="redirectDropdown" onchange="redirectPage(this)">
                                        <option value="{{ route('mark_attendance.index') }}">All Employees</option>
                                        @foreach ($data['employees'] as $employee)
                                            <option value="{{ URL::to('mark_attendance/'.$employee->slug) }}" {{ $user->slug==$employee->slug?"selected":"" }} >
                                                {{ $employee->first_name }} {{ $employee->last_name }} ({{ $employee->profile->employment_id??'-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif --}}

                        @php $url = URL::to('mark_attendance') @endphp
                        @include('admin.layouts.employee-dropdown', ['employees' => $data['employees'], 'user' => $user, 'url' => $url, 'type' => 'redirect'])
                    </div>
                </div>
                <hr />

                <div class="card-datatable">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container">
                            <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table">
                                <thead>
                                    <tr>
                                        <th scope="col">S.No#</th>
                                        <th scope="col">Employee</th>
                                        <th scope="col">Mark Type</th>
                                        <th scope="col">Adjusted Date</th>
                                        <th scope="col">Created At</th>
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
                    { data: 'employee_id', name: 'employee_id' },
                    { data: 'mark_type', name: 'mark_type' },
                    { data: 'attendance_id', name: 'attendance_id' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endpush
