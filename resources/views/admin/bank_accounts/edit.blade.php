@extends('admin.layouts.app')
@section('title', $title.' - ' . appName())

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">
                            <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Home /</span> Bank Account</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Users List Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $title }}</h5>
                </div>
                <div class="card-datatable table-responsive">
                    <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="container border-top">
                            <div class="row">
                                <div class="col-sm-12">
                                    <!-- Add role form -->
                                    <form id="edit_bank_details" class="pt-0 fv-plugins-bootstrap5 fv-plugins-framework mt-3" method="POST" action="{{ route('bank_accounts.update', $model->id) }}">
                                        @csrf
                                        {{ method_field('PATCH') }}
                                        <div class="row">
                                            @if(!empty($employees))
                                                <div class="col-md-6">
                                                    <div class="mb-3 fv-plugins-icon-container">
                                                        <label class="form-label" for="user_id">Employee <span class="text-danger">*</span></label>
                                                        <select class="form-select select2" id="user_id", name="user_id">
                                                            <option value="">Select Employee</option>
                                                            @foreach($employees as $employee)
                                                                <option value="{{ $employee->id }}" {{ $model->user_id==$employee->id?'selected':'' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
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
                                                    <input type="text" class="form-control" id="bank_name" value="{{ $model->bank_name }}" placeholder="Enter bank name" name="bank_name">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    <span id="bank_name_error" class="text-danger error">{{ $errors->first('bank_name') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 fv-plugins-icon-container">
                                                    <label class="form-label" for="branch_code">Branch Code <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control branch_code" maxlength="4" minlength="4" id="branch_code" value="{{ $model->branch_code }}" placeholder="Enter branch code" name="branch_code">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    <span id="branch_code_error" class="text-danger error">{{ $errors->first('branch_code') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 fv-plugins-icon-container">
                                                    <label class="form-label" for="iban">IBAN <span class="text-danger">*</span></label>
                                                    <input type="text" maxlength="25" minlength="16" id="iban" class="form-control iban" value="{{ $model->iban }}" placeholder="Enter iban number" name="iban">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    <span id="iban_error" class="text-danger error">{{ $errors->first('iban') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="account">Account Number <span class="text-danger">*</span></label>
                                                    <input type="text" id="account" maxlength="20" minlength="10" name="account" value="{{ $model->account }}" class="form-control account_number" placeholder="Enter account">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    <span id="account_number_error" class="text-danger error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label" for="title">Account Title <span class="text-danger">*</span></label>
                                                    <input type="text" id="title" name="title" value="{{ $model->title }}" class="form-control" placeholder="Enter account title">
                                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                                    <span id="title_error" class="text-danger error">{{ $errors->first('title') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-12 text-start my-4">
                                                <button type="button" id="editButton" class="btn btn-primary me-sm-3 me-1 d-none">Edit</button>
                                                <button type="submit" id="updateButton" class="btn btn-primary me-sm-3 me-1">Update</button>
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
@endsection
@push('js')
    <script>
        // $(document).ready(function () {
        //     $('#edit_bank_details input').prop('disabled', true);
        
        //     $('#editButton').click(function() {
        //       $(this).addClass('d-none');
        //       $('#updateButton').removeClass('d-none');
        //       $('#edit_bank_details input').prop('disabled', false);
        //     });
        // });
        
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
            }else{
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
              $('#account_number_error').html(message); 
              return false;
            }else{
              $('#account_number_error').html('');  
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
            }else{
              $('#branch_code_error').html('');  
              return true;
            }
          });
        });
        
        $("#edit_bank_details").submit(function (event) {
            event.preventDefault();
            var bank_name = $('#bank_name').val();
            var iban = $('#iban').val();
            var account_number = $('#account').val();
            var branch_code = $('#branch_code').val();
            var title = $('#title').val();
            
            var isValid = true;
            if(title == ''){
                $('#title_error').html('Account title is required.');
                isValid = false;
            }else{
                $('#title_error').html('');
            }
            if(bank_name == ''){
                $('#bank_name_error').html('Bank name is required.');
                isValid = false;
            }else{
                $('#bank_name_error').html('');
            }
            if(iban==''){
                $('#iban_error').html('IBAN is required.');
                isValid = false;
            }else if(iban.length < 16){
                $('#iban_error').html('IBAN length is short.');
                isValid = false;
            }else{
                $('#iban_error').html('');
            }
            if(account_number == ''){
                $('#account_number_error').html('Account number is required.');
                isValid = false;
            }else if(account_number.length < 10){
                $('#account_number_error').html('Account number length is short.');
                isValid = false;
            }else{
                $('#account_number_error').html('');
            }
            if(branch_code==''){
                $('#branch_code_error').html('Branch code is required.');
                isValid = false;
            }else if(branch_code.length < 4){
                $('#branch_code_error').html('Branch code length is short.');
                isValid = false;
            }else{
                $('#branch_code_error').html('');
            }
            
            if (isValid) {
                this.submit(); // Submit the form
            }
        });

    </script>
@endpush
