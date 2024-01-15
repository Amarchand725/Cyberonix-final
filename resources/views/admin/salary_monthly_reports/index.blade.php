@extends('admin.layouts.app')
@section('title', $title.' - '. appName())

@push('styles')
@endpush

@section('content')
@if(isset($url) && !empty($url))
<input type="hidden" id="page_url" value="{{ URL::to('monthly_salary_reports/monthly_report') }}/{{ $month }}/{{ $year }}">
@else
<input type="hidden" id="page_url" value="{{ route('monthly_salary_reports.index') }}">
@endif

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-header">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> {{ $title }} of <span id="append_month_year"></span></h4>

                    </div>

                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        <div class="dt-buttons flex-wrap">
                            <!-- <button type="button" class="btn btn-primary waves-effect waves-light me-3" data-monthly-report-start-month-year="{{ $month_year }}" data-current-month="{{ $selectMonth }}" id="Slipbutton">Select Month<i class="ti ti-chevron-down ms-2"></i></button> -->
                            <!-- {{ URL::to('monthly_salary_reports/export_monthly_salary_report/download') }}/{{ $month }}/{{ $year }} -->
                            <a data-toggle="tooltip" data-placement="top" title="Export Monthly Salary Sheet" href="javascript:;" class="btn btn-label-success me-4" id="export-to-excel">
                                <span>
                                    <i class="fa fa-file-excel me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Export as Excel </span>
                                </span>
                            </a>
                            <div class="dt-buttons btn-group flex-wrap">
                                <button data-toggle="tooltip" data-placement="top" title="Refresh " type="button" class="btn btn-secondary add-new btn-primary me-3" id="refresh-btn" data-url="{{ route('assets.index') }}">
                                    <span>
                                        <i class="ti ti-refresh ti-sm"></i>
                                        <span class="d-none d-sm-inline-block">Refresh</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-4">
                <div class="col-md-4">
                    <label for="">Departments</label>
                    <select name="department" class="form-select select2" id="department">
                        <option value="">All</option>
                        @if(!empty(getAllDepartments()))
                        @foreach(getAllDepartments() as $department)
                        <option value="{{$department->id ?? ''}}">{{$department->name ?? ''}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="">Month</label>
                    <input type="text" class="form-control month" id="month"  data-start-date="{{$month_year ?? ''}}" name="month" min="" />
                    <input type="hidden" value="{{$month_year ?? ''}}" class="month-hidden">
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
                                    <th>Bank</th>
                                    <th>Actual Salary</th>
                                    <th>Car Allowance</th>
                                    <th>Earning</th>
                                    <th>Approved Days Amount</th>
                                    <th>Deduction</th>
                                    <th>Net Salary</th>
                                    <!--<th>Total</th>-->
                                    <!--<th>Earning</th>-->
                                    <!--<th>Absent</th>-->
                                    <!--<th>Late In</th>-->
                                    <!--<th>Half Days</th>-->
                                    <th>Created At</th>
                                    <th>Actions</th>
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
<script src="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script>
    $(function() {
        var currentMonth = $('#Slipbutton').data('current-month');
        var monthlyReportStartYearMonth = $('#Slipbutton').data('monthly-report-start-month-year');

        $('#Slipbutton').datepicker({
            format: 'mm/yyyy',
            startView: 'year',
            minViewMode: 'months',
            startDate: monthlyReportStartYearMonth,
            endDate: currentMonth,
        }).on('changeMonth', function(e) {
            var selectedMonth = String(e.date.getMonth() + 1).padStart(2, '0');
            var selectedYear = e.date.getFullYear();

            var selectOptionUrl = "{{ URL::to('monthly_salary_reports/monthly_report') }}/" + selectedMonth + "/" + selectedYear;

            window.location.href = selectOptionUrl;
        });
    });

    var currentMonth = $('#month').data('start-date');

$('#month').datepicker({
        format: 'mm/yyyy',
        startView: 'year',
        minViewMode: 'months',
        startDate: currentMonth,
        endDate: new Date(),
    }).on('changeMonth', function(e) {
        var formattedDate = e.date.toLocaleString('default', { month: 'long' }) + ' ' + e.date.getFullYear();
        $(this).val(formattedDate);
        var submittedFormat = e.date.getFullYear() + '-' + String(e.date.getMonth() + 1).padStart(2, '0');
        $('.month-hidden').val(submittedFormat);
        loadPageData();
    });

    //datatable
    $(document).ready(function() {
        loadPageData()
    });
    $(document).on("click", "#refresh-btn", function() {
        loadPageData()
    });

    function loadPageData() {
        var table = $('.data_table').DataTable();
        if ($.fn.DataTable.isDataTable('.data_table')) {
            table.destroy();
        }
        var page_url = $('#page_url').val();
        var table = $('.data_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: page_url + "?loaddata=yes",
                type: "GET",
                data: function(d) {
                    d.month = $('.month-hidden').val();
                    d.department = $('#department').val();
                    d.search = $('input[type="search"]').val();
                },
                error: function(xhr, error, code) {
                    console.log(xhr);
                    console.log(error);
                    console.log(code);
                }
            },

            "initComplete": function(settings, json) {
                var getMonthValue = $('.month-hidden').val();
                if (getMonthValue) {
                    var month = moment(getMonthValue, 'YYYY-MM').format('M');
                    var year = moment(getMonthValue, 'YYYY-MM').format('Y');
                    if (month && year) {
                        var month = GetMonthName(month);
                        var month_year = " " + month + " " + year;
                        if (month_year) {
                            $("#append_month_year").html("/" + month_year);
                        }
                    }
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'employee_id',
                    name: 'employee_id'
                },
                {
                    data: 'bank',
                    name: 'bank'
                },
                {
                    data: 'actual_salary',
                    name: 'actual_salary'
                },
                {
                    data: 'car_allowance',
                    name: 'car_allowance'
                },
                {
                    data: 'earning_salary',
                    name: 'earning_salary'
                },
                {
                    data: 'approved_days_amount',
                    name: 'approved_days_amount'
                },
                {
                    data: 'deduction',
                    name: 'deduction'
                },
                {
                    data: 'net_salary',
                    name: 'net_salary'
                },
            //   {
            //         data: 'total_days',
            //         name: 'total_days'
            //     },
            //     {
            //         data: 'earning_days',
            //         name: 'earning_days'
            //     },

            //     {
            //         data: 'absent_days',
            //         name: 'absent_days'
            //     },

            //     {
            //         data: 'late_in_days',
            //         name: 'late_in_days'
            //     },

            //     {
            //         data: 'half_days',
            //         name: 'half_days'
            //     },
                {
                    data: 'generated_date',
                    name: 'generated_date'
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

    function GetMonthName(monthNumber) {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return months[monthNumber - 1];
    }
    $(document).on("change", "#department", function() {
        // console.log($(this).val());
        loadPageData()
    });



    // $(document).on("change", "#month", function() {
    //     // console.log($(this).val());
    //     loadPageData()
    // });



    $(document).on("click", "#export-to-excel", function() {
        $.ajax({
            url: "{{route('monthly_salary_reports.export_monthly_salary_report.download')}}",
            method: 'GET',
            data: {
                month: $('.month-hidden').val(),
                department: $('#department').val(),
                search: $('input[type="search"]').val(),
            },
            success: function(response) {
                toastr.success("Please Wait while the report is being generated")
                window.location.href = response;
            },
            error: function(error) {
                // Handle error
                toastr.error("Some thing went wrong!");
            }
        });
    });
</script>
@endpush
