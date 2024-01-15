@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
@if(isset($data['users']))
<input type="hidden" id="page_url" value="{{ route('vehicle_allowances.index') }}">
@else
<input type="hidden" id="page_url" value="{{ route('vehicle_allowances.trashed') }}">
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
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-item-center mt-4">
                        @if(isset($data['users']))
                        <div class="dt-buttons flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="All Trashed Records" href="{{ route('vehicle_allowances.trashed') }}" class="btn btn-label-danger mx-1">
                                <span>
                                    <i class="ti ti-trash me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">All Trashed Records </span>
                                </span>
                            </a>
                        </div>
                        <div class="dt-buttons btn-group flex-wrap">
                            <button data-toggle="tooltip" data-placement="top" title="Add Allowance" type="button" class="btn btn-secondary add-new btn-primary mx-3" id="add-btn" data-url="{{ route('vehicle_allowances.store') }}" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#offcanvasAddAnnouncement">
                                <span>
                                    <i class="ti ti-plus me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">Add New</span>
                                </span>
                            </button>
                        </div>
                        @else
                        <div class="dt-buttons btn-group flex-wrap">
                            <a data-toggle="tooltip" data-placement="top" title="Show All Records" href="{{ route('vehicle_allowances.index') }}" class="btn btn-success btn-primary mx-3">
                                <span>
                                    <i class="ti ti-eye me-0 me-sm-1 ti-xs"></i>
                                    <span class="d-none d-sm-inline-block">View All Records</span>
                                </span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-datatable">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <table class="dt-row-grouping table dataTable dtr-column border-top table-border data_table table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">S.No#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Vehicle</th>
                                    <th scope="col">Allowance </th>
                                    <th scope="col">Effective Date</th>
                                    <th scope="col">End Date</th>
                                    <th scope="col">Status</th>
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

<!-- Add Employment Status Modal -->
<div class="modal fade" id="offcanvasAddAnnouncement" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-top modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-4">
                    <h3 class="mb-2" id="modal-label"></h3>
                </div>
                <form id="create-form" class="row g-3" data-method="" data-modal-id="offcanvasAddAnnouncement">
                    @csrf

                    <span id="edit-content">
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="user">User <span class="text-danger">*</span></label>
                            <select name="user" id="user" class="form-control">
                                <option value="" selected>Select User</option>
                                @if(isset($data['users']))
                                @foreach ($data['users'] as $user)
                                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                                @endforeach
                                @endif
                            </select>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="user_error" class="text-danger error"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="vehicle">Vehicle <span class="text-danger">*</span></label>
                            <input type="text" id="vehicle" name="vehicle" value="" class="form-control" placeholder="Enter vehicle name" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="vehicle_error" class="text-danger error"></span>
                        </div>
                        <div class="col-md-12 mt-2">
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
                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="allowance">Allwonce<span class="text-danger">*</span></label>
                            <input type="number" id="allowance" name="allowance" value="" class="form-control" placeholder="Enter allowance" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="allowance_error" class="text-danger error"></span>
                        </div>

                        <div class="col-md-12 mt-2">
                            <label class="form-label" for="effective_date">Effective Date <span class="text-danger">*</span></label>
                            <input type="date" id="effective_date" name="effective_date" class="form-control" />
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="effective_date_error" class="text-danger error"></span>
                        </div>

                        <div class="col-12 col-md-12 mt-2">
                            <label class="form-label" for="note">Note</label>
                            <textarea class="form-control" rows="5" name="note" id="note" placeholder="Enter note here...."></textarea>
                            <div class="fv-plugins-message-container invalid-feedback"></div>
                            <span id="note_error" class="text-danger error"></span>
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
<!--/ Edit Employment Status Modal -->

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

                <div class="col-12 mt-3 text-end">
                    <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
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
    function selectInit() {
        setTimeout(() => {
            $('.select2').each(function() {
                $(this).select2({
                    // theme: 'bootstrap-5',
                    dropdownParent: $(this).parent(),
                });
            });
        }, 1000);
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
            ajax: page_url + "?loaddata=yes",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'vehicle',
                    name: 'vehicle'
                },
                {
                    data: 'allowance',
                    name: 'allowance'
                },
                {
                    data: 'effective_date',
                    name: 'effective_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
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
    });
</script>

<!-- currency script -->
<script>
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
    $(document).on("keyup", "#allowance", function() {
        var code = $("#currency").val();
        var salary = $(this).val();
        if (code && code != "PKR") {
            convertCurrency(code, salary)
        } else {
            $(".currency_rate_after_conversion").html("")
        }
    });
    // for employee create and edit modal  currency rate on change 



    function convertCurrency(code, salary) {
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
<!-- currency script -->

@endpush