<!DOCTYPE html>
<html lang="en">

<head>
    <title>Salary Slip of {{date("F Y", strtotime($data['year'].'-'.$data['month']))}}</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://portal.cyberonix.co/public/admin/assets/vendor/fonts/tabler-icons.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if(!empty(settings()->favicon))
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ settings()->favicon }}" />
    @else
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/favicon.ico" />
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        a:hover {
            text-decoration: none;
        }

        .stamp-logo {
            width: 10%;
        }

        .form-fields a {
            background: linear-gradient(72.47deg, rgba(234, 84, 85) 22.16%, rgba(234, 84, 85, 0.7) 76.47%);
            /*box-shadow: 0px 2px 6px 0px rgba(234,84,85, 0.48);*/
            color: #fff;
            padding: 6px 11px;
            border-radius: 2px;
            font-weight: 400;
        }

        .form-fields button,
        .form-fields button:focus {
            background: linear-gradient(72.47deg, rgba(234, 84, 85) 22.16%, rgba(234, 84, 85, 0.7) 76.47%);
            /*box-shadow: 0px 2px 6px 0px rgba(234,84,85, 0.48);*/
            padding: 5px 25px;
            border-radius: 2px;
            border: none;
            outline: none;
            color: #fff;
        }

        .Cyberonix-Logo {
            width: 25%;
        }

        .btn-primary {
            color: #fff !important;
            background-color: #e30b5c !important;
            border-color: #e30b5c !important;
            border-radius: 0.375rem !important;
            outline: none !important;
        }

        .btn-label-primary {
            color: #e30b5c !important;
            border-color: transparent !important;
            background: #fbdae7 !important;
            border-radius: 0.375rem !important;
            outline: none !important;
        }


        @media print {

            #pdfPrint,
            .form-fields {
                display: none !important;
            }
        }
    </style>
</head>

<body class="py-5">
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="form-fields d-inline-block pull-right mt-3 float-right">
                        {{-- <a href="{{ route('employees.salary_details') }}" class=text-capitalize mr-3 btn-label-primary"> <i class="fa fa-back me-1"></i> Go Back </a> --}}
                        <a href="{{ URL::to('employees/salary_details/'.$data['month'].'/'.$data['year'].'/'.$data['user']->slug) }}" class="text-capitalize mr-3 btn-label-primary"> <i class="fa fa-back me-1"></i> Go Back </a>
                        <button id="pdfPrint" onclick="forPrint()" class="text-capitalize btn-primary" type="button" name="pdfPrint">
                            <span><i class="ti ti-printer me-1"></i></span>Print
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <div class="text-center">
                        @if(isset(settings()->logo) && !empty(settings()->black_logo))
                        <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" style="width:25%" class="light-logo img-logo" alt="{{ settings()->name }}" />
                        @else
                        <img src="{{ asset('public/admin/default.png') }}" class="img-fluid light-logo img-logo" title="Company Black Logo Here..." alt="Default" />
                        @endif
                        <h6 class="mt-2 font-weight-bold h5">Salary Slip for the month of {{date("F Y", strtotime($data['year'].'-'.$data['month']))}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="mt-3 salary-table">
        <div class="container">
            @if(Auth::user()->hasPermissionTo('payslip_editable-create'))
            <div class="row">
                <div class="col-md-6 pr-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" contenteditable>Employee No. </th>
                                <td contenteditable>
                                    @if(isset($data['user']->profile) && !empty($data['user']->profile->employment_id))
                                    {{ $data['user']->profile->employment_id }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>CNIC #</th>
                                <td contenteditable>
                                    @if(!empty($data['user']->profile->cnic))
                                    {{ $data['user']->profile->cnic }}
                                    @else
                                    @if(isset($data['user']->hasPreEmployee) && !empty($data['user']->hasPreEmployee->cnic))
                                    {{ $data['user']->hasPreEmployee->cnic }}
                                    @else
                                    N/A
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Designation </th>
                                <td contenteditable>
                                    @if(isset($data['user']->jobHistory->designation->title) && !empty($data['user']->jobHistory->designation->title))
                                    {{ $data['user']->jobHistory->designation->title }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Total Days </th>
                                <td contenteditable>{{ $data['totalDays'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Per Day Salary </th>
                                <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['per_day_salary']) }}</td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Bank </th>
                                <td contenteditable>N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 pl-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" contenteditable>Employee Name</th>
                                <td contenteditable>{{ $data['user']->first_name }} {{ $data['user']->last_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Appointment Date</th>
                                <td contenteditable>
                                    @if(isset($data['user']->profile) && !empty($data['user']->profile->joining_date))
                                    {{ date('d M Y', strtotime($data['user']->profile->joining_date)) }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Department</th>
                                <td contenteditable>
                                    @if(isset($data['user']->departmentBridge->department) && !empty($data['user']->departmentBridge->department->name))
                                    {{ $data['user']->departmentBridge->department->name }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Earning Days</th>
                                <td contenteditable>{{ $data['total_earning_days'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Pay Through</th>
                                <td contenteditable>N/A</td>
                            </tr>
                            <tr>
                                <th scope="row" contenteditable>Branch </th>
                                <td contenteditable>N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 pr-0">
                    <div class="custom-table">
                        <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0" contenteditable>Salary & Allowances</h5>
                        <table class="table table-bordered mb-0 col-md-12">
                            <thead>
                                <tr>
                                    <th scope="row" contenteditable>Title </th>
                                    <th scope="row" contenteditable>Actual </th>
                                    <th scope="row" contenteditable>Earning </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" contenteditable>Basic </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['salary']) }} </td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['earning_days_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>House Rent</th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Medical </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Cost Of Living Allowance</th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0 </td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Special </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Car Allowance </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['car_allowance']) }}</td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['car_allowance']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Arrears </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Extra Days Amount </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-center" contenteditable>Total</th>
                                    <th scope="row" contenteditable>{{$currency_code ?? "Rs."}} {{ $data['total_actual_salary'] }}</th>
                                    <th scope="row" contenteditable>{{$currency_code ?? "Rs."}} {{ $data['total_earning_salary'] }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 pl-0">
                    <div class="custom-table">
                        <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0" contenteditable>Deductions</h5>
                        <table class="table table-bordered mb-0 col-md-12">
                            <thead>
                                <tr>
                                    <th scope="row" contenteditable>Title </th>
                                    <th scope="row" contenteditable>Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" contenteditable>Absent Days Amount </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['absent_days_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Half Days Amount</th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['half_days_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Late In + Early Out Amount </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} {{ number_format($data['late_in_early_out_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Income Tax</th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>EOBI </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Loan Installment </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row" contenteditable>Advance Salary </th>
                                    <td contenteditable>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr class="invisible">
                                    <th scope="row">Advance Salary </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-center" contenteditable>NET SALARY</th>
                                    <th scope="row" contenteditable>{{$currency_code ?? "Rs."}} {{ $data['net_salary'] }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-md-6 pr-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Employee No. </th>
                                <td>
                                    @if(isset($data['user']->profile) && !empty($data['user']->profile->employment_id))
                                    {{ $data['user']->profile->employment_id }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">CNIC #</th>
                                <td>
                                    @if(!empty($data['user']->profile->cnic))
                                    {{ $data['user']->profile->cnic }}
                                    @else
                                    @if(isset($data['user']->hasPreEmployee) && !empty($data['user']->hasPreEmployee->cnic))
                                    {{ $data['user']->hasPreEmployee->cnic }}
                                    @else
                                    N/A
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Designation </th>
                                <td>
                                    @if(isset($data['user']->jobHistory->designation->title) && !empty($data['user']->jobHistory->designation->title))
                                    {{ $data['user']->jobHistory->designation->title }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Total Days </th>
                                <td>{{ $data['totalDays'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Per Day Salary </th>
                                <td>{{$currency_code ?? "Rs."}} {{ number_format($data['per_day_salary']) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Bank </th>
                                <td>N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 pl-0">
                    <table class="table table-bordered mb-0">
                        <tbody>
                            <tr>
                                <th scope="row">Employee Name</th>
                                <td>{{ $data['user']->first_name }} {{ $data['user']->last_name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Appointment Date</th>
                                <td>
                                    @if(isset($data['user']->profile) && !empty($data['user']->profile->joining_date))
                                    {{ date('d M Y', strtotime($data['user']->profile->joining_date)) }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Department</th>
                                <td>
                                    @if(isset($data['user']->departmentBridge->department) && !empty($data['user']->departmentBridge->department->name))
                                    {{ $data['user']->departmentBridge->department->name }}
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Earning Days</th>
                                <td>{{ $data['total_earning_days'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Pay Through</th>
                                <td>N/A</td>
                            </tr>
                            <tr>
                                <th scope="row">Branch </th>
                                <td>N/A</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 pr-0">
                    <div class="custom-table">
                        <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0">Salary & Allowances</h5>
                        <table class="table table-bordered mb-0 col-md-12">
                            <thead>
                                <tr>
                                    <th scope="row">Title </th>
                                    <th scope="row">Actual </th>
                                    <th scope="row">Earning </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Basic </th>
                                    <td>{{ number_format($data['salary']) }} </td>
                                    <td>{{ number_format($data['earning_days_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">House Rent</th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                    <td>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row">Medical </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row">Cost Of Living Allowance</th>
                                    <td>{{$currency_code ?? "Rs."}} 0 </td>
                                    <td>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row">Special </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                    <td>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row">Car Allowance </th>
                                    <td>{{$currency_code ?? "Rs."}} {{ number_format($data['car_allowance']) }}</td>
                                    <td>{{$currency_code ?? "Rs."}} {{ number_format($data['car_allowance']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Arrears </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                    <td>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row">Extra Days Amount </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                    <td>{{$currency_code ?? "Rs."}} 0 </td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-center">Total</th>
                                    <th scope="row">{{$currency_code ?? "Rs."}} {{ $data['total_actual_salary'] }}</th>
                                    <th scope="row">{{$currency_code ?? "Rs."}} {{ $data['total_earning_salary'] }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 pl-0">
                    <div class="custom-table">
                        <h5 class="border text-center py-3 border-top-0 mb-0 border-bottom-0">Deductions</h5>
                        <table class="table table-bordered mb-0 col-md-12">
                            <thead>
                                <tr>
                                    <th scope="row">Title </th>
                                    <th scope="row">Amount </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Absent Days Amount </th>
                                    <td>{{$currency_code ?? "Rs."}} {{ number_format($data['absent_days_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Half Days Amount</th>
                                    <td>{{$currency_code ?? "Rs."}} {{ number_format($data['half_days_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Late In + Early Out Amount </th>
                                    <td>{{$currency_code ?? "Rs."}} {{ number_format($data['late_in_early_out_amount']) }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Income Tax</th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row">EOBI </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row">Loan Installment </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row">Advance Salary </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr class="invisible">
                                    <th scope="row">Advance Salary </th>
                                    <td>{{$currency_code ?? "Rs."}} 0</td>
                                </tr>
                                <tr>
                                    <th scope="row" class="text-center">NET SALARY</th>
                                    <th scope="row">{{$currency_code ?? "Rs."}} {{ $data['net_salary'] }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <p class="mt-5" contenteditable><span class="font-weight-bold">Note: </span>This is a computer generated salary slip and does not require signatures.</p>
                    @if(isset(settings()->logo) && !empty(settings()->slip_stamp))
                    <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->slip_stamp }}" class="img-fluid light-logo stamp-logo" alt="{{ settings()->name }}" />
                    @else
                    <img src="{{ asset('public/admin/default.png') }}" class="img-fluid light-logo stamp-logo" title="Company Slip Stamp Here..." alt="Default" />
                    @endif
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js"></script>
    <script>
        function forPrint() {
            window.print();
        }
    </script>
</body>

</html>
