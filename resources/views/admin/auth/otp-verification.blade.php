@extends('admin.auth.layouts.app')
@section('title', $title. ' - '. appName())
@section('content')
<div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner py-4">
        <!-- Login -->
        <div class="card">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
              <a href="javascript:void(0);" class="app-brand-link gap-2">
                <span class="app-brand-text demo text-body fw-bold ms-1">Otp Verification</span>
              </a>
            </div>
            <!-- /Logo -->
            {{--  <h4 class="mb-1 pt-2">Welcome to Vuexy! 👋</h4>  --}}
            <p class="mb-4">Please enter otp your account and start the adventure</p>
            <div id="errorMessage"></div>
            <form id="otpForm" action="{{ route('admin.otpVerification') }}" method="POST">
                @csrf

                <input type="hidden" name="user_id" value="{{ isset($user_id) && !empty($user_id) ? $user_id : null }}">
                <input type="hidden" name="remember" value="{{ isset($remember) && !empty($remember) ? $remember : null }}">
                <div class="mb-3">
                    <label for="otp" class="form-label">Enter Otp</label>
                    <input
                        type="number"
                        class="form-control"
                        id="otp"
                        name="otp"
                        placeholder="Enter your Otp"
                        autofocus
                    />
                    <span class="text-danger otp_errors">{{ $errors->first('otp') }}</span>
                </div>
                <div class="col-12 mt-3">
                    <span id="otp-btn">
                        <button type="submit" id="otpButton" class="btn btn-primary d-grid w-100">Verify </button>
                    </span>

                    <div id="loader" style="display: none;">
                        <button type="button" class="btn btn-primary w-100" disabled><span class="spinner-border me-1" role="status" aria-hidden="true"></span>Loading...</button>
                    </div>
                </div>
            </form>


            </div>
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $("#otpButton").click(function(e) {
                e.preventDefault(); // Prevent the default form submission
                var url = $("#otpForm").attr('action');
                // Show the loader
                $('#otp-btn').hide();
                $("#loader").show();

                // Hide the error message if it's currently displayed
                $("#errorMessage").hide();

                // Perform your AJAX form submission here (e.g., using $.post or $.ajax)
                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#otpForm").serialize(), // Serialize form data
                    success: function(response) {
                        // Check the response for validation errors
                        if (response.success == true) {
                            // If login is successful, you can redirect or perform other actions
                            var setLocal = localStorage.setItem(response.secretKeyName, response.user_verification_key);
                            window.location.href = "{{ route('dashboard') }}";
                        } else {
                            // If there are validation errors, hide the loader and display the error message
                            $("#loader").hide();
                            $('#otp-btn').show();
                            var html = '<div class="alert alert-danger">'+response.error+'</div>';
                            $("#errorMessage").html(html).show();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors here
                        $("#loader").hide();
                        $('#otp-btn').show();
                        var html = '<div class="alert alert-danger">'+error+'</div>';
                        $("#errorMessage").html(html).show();
                    }
                });
            });
        });
    </script>
@endpush
