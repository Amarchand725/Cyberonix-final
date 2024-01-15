@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> Bank Account</h4>
                    </div>
                    @can("bank_accounts-import")
                    <div class="col-md-6" style="text-align: right;">
                        <a data-toggle="tooltip" data-placement="top" title="Import Bank Accounts" href="javascript:;" class="btn btn-label-success me-3 import_bank_accounts">
                            <span>
                                <i class="fa fa-file-excel me-0 me-sm-1 ti-xs"></i>
                                <span class="d-none d-sm-inline-block">Import CSV </span>
                            </span>
                        </a>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        <!-- Users List Table -->
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">{{ $title }}</h5>
            </div>
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12">
                                <!-- Add role form -->
                                <form class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework mt-3" method="POST" action="{{ route('bank_accounts.store') }}">
                                    @csrf
                                    <div class="row">
                                        @if(!empty($employees))
                                        <div class="col-md-6">
                                            <div class="mb-3 fv-plugins-icon-container">
                                                <label class="form-label" for="user_id">Employee <span class="text-danger">*</span></label>
                                                <select class="form-select select2" id="user_id" , name="user_id">
                                                    <option value="">Select Employee</option>
                                                    @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="user_id_error" class="text-danger error">{{ $errors->first('user_id') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                        @endif
                                        <div class="col-md-6">
                                            <div class="mb-3 fv-plugins-icon-container">
                                                <label class="form-label" for="bank_name">Bank Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" value="{{ old('bank_name') }}" id="bank_name" placeholder="Enter bank name" name="bank_name">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="bank_name_error" class="text-danger error">{{ $errors->first('bank_name') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 fv-plugins-icon-container">
                                                <label class="form-label" for="branch_code">Branch Code <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control branch_code" value="{{ old('branch_code') }}" maxlength="4" minlength="4" id="branch_code" placeholder="Enter branch code" name="branch_code">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="branch_code_error" class="text-danger error">{{ $errors->first('branch_code') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 fv-plugins-icon-container">
                                                <label class="form-label" for="iban">IBAN <span class="text-danger">*</span></label>
                                                <input type="text" id="iban" maxlength="25" minlength="16" value="{{ old('iban') }}" class="form-control iban" placeholder="Enter iban number" name="iban">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="iban_error" class="text-danger error">{{ $errors->first('iban') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="account">Account Number <span class="text-danger">*</span></label>
                                                <input type="text" id="account" maxlength="20" minlength="10" value="{{ old('account') }}" name="account" class="form-control account_number" placeholder="Enter account number">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="account_error" class="text-danger error">{{ $errors->first('account') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="title">Account Title <span class="text-danger">*</span></label>
                                                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="Enter account title">
                                                <div class="fv-plugins-message-container invalid-feedback"></div>
                                                <span id="title_error" class="text-danger error">{{ $errors->first('title') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3 action-btn">
                                            <div class="demo-inline-spacing sub-btn">
                                                <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <!--/ Add role form -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- import-bank-modals -->
<div class="modal fade" id="import-bank-modals" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
        <div class="modal-content p-3 p-md-5">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <h3 class="mb-2" id="modal-label">Import CSV</h3>
            </div>
            <form id="importBankAccounts" method="post" enctype="multipart/form-data" class="row gy-1 gx-2">
                @csrf
                <div class="modal-body">
                    <div class="col-12 col-md-12">
                        <label class="form-label" for="files">Files (CSV)</label>
                        <input type="file" id="file" name="file" class="form-control file_input" accept=".csv" />
                        <span class="text-danger file_input_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-12 mt-3" style="text-align: center;">
                        <button type="submit" class="btn btn-label-success  submitBtn" aria-label="Close">
                            <span class="spinner-border  submitBtnSpinner  d-none me-1" role="status" aria-hidden="true"></span>
                            Import
                        </button>
                        <button type="reset" class="btn btn-label-primary btn-reset" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- import-bank-modals -->

@endsection
@push('js')
<script>
    $(document).on('keyup', '.branch_code', function() {
        var branch_code = $(this).val();
        var formattedbranch_code = formatBranchCode(branch_code);
        $(this).val(formattedbranch_code);
    });

    function formatBranchCode(branch_code) {
        branch_code = branch_code.replace(/\D/g, ''); // Remove non-numeric characters
        if (branch_code.length > 4) {
            branch_code = branch_code.substring(0, 4);
        }
        return branch_code;
    }

    $(document).ready(function() {
        $(document).on('keyup', '.iban', function() {
            // Enforce minimum and maximum length
            var minLength = parseInt($(this).attr('minlength'));
            var maxLength = parseInt($(this).attr('maxlength'));

            if ($(this).val().length < minLength) {
                // Input is shorter than the minimum length
                var message = 'Account number is too short. Minimum length is ' + minLength + ' digits.';
                $('#iban_error').html(message);
                return false;
            } else {
                $('#iban_error').html('');
                return true;
            }
        });
        $(document).on('keyup', '.account_number', function() {
            // Remove any non-digit characters
            $(this).val($(this).val().replace(/\D/g, ''));

            // Enforce minimum and maximum length
            var minLength = parseInt($(this).attr('minlength'));
            var maxLength = parseInt($(this).attr('maxlength'));

            if ($(this).val().length < minLength) {
                // Input is shorter than the minimum length
                var message = 'Account number is too short. Minimum length is ' + minLength + ' digits.';
                $('#account_error').html(message);
                return false;
            } else {
                $('#account_error').html('');
                return true;
            }
        });
        $(document).on('keyup', '.branch_code', function() {
            // Remove any non-digit characters
            $(this).val($(this).val().replace(/\D/g, ''));

            // Enforce minimum and maximum length
            var minLength = parseInt($(this).attr('minlength'));
            var maxLength = parseInt($(this).attr('maxlength'));

            if ($(this).val().length < minLength) {
                // Input is shorter than the minimum length
                var message = 'Branch code is too short. Minimum length is ' + minLength + ' digits.';
                $('#branch_code_error').html(message);
                return false;
            } else {
                $('#branch_code_error').html('');
                return true;
            }
        });
    });

    $(document).on("click", ".import_bank_accounts", function() {
        $("#import-bank-modals").modal("show")
    });
    $(document).ready(function() {

        $('.submitBtn').click(function() {
            $(".file_input_error").html("")
            // Get the selected file
            var files = $('#file')[0].files;

            if (files.length > 0) {
                var fd = new FormData();

                // Append data 
                fd.append('file', files[0]);
                fd.append('_token', "{{csrf_token()}}");

                // AJAX request 
                $.ajax({
                    url: "{{ route('bank_accounts.import') }}",
                    method: 'post',
                    data: fd,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        if ($(".submitBtnSpinner").hasClass('d-none')) {
                            $(".submitBtnSpinner").removeClass('d-none')
                        }
                        $(".submitBtn").prop('disabled', true);
                    },
                    success: function(res) {
                        $(".file_input").val("")
                        showSuccess(res.message)
                        $("#import-bank-modals").modal("hide")
                        $(".submitBtn").prop('disabled', false);

                        if (!$(".submitBtnSpinner").hasClass('d-none')) {
                            $(".submitBtnSpinner").addClass('d-none')
                        }
                    },
                    error: function(xhr, status, error) {
                        $(".file_input_error").html(error)
                        $(".submitBtn").prop('disabled', false);
                        if (!$(".submitBtnSpinner").hasClass('d-none')) {
                            $(".submitBtnSpinner").addClass('d-none')
                        }
                    }
                });
            } else {
                $(".file_input_error").html("Please select a file")

            }

        });
    });
</script>
@endpush