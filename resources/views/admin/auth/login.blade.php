@extends('admin.auth.layouts.app')
@section('title', $title. ' - '. appName())
@section('content')
<div class="authentication-wrapper authentication-cover authentication-bg">
    <div class="authentication-inner row">
        <div class="d-none d-lg-flex col-lg-7 p-0">
            <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                <img src="{{ asset('public/admin') }}/assets/img/illustrations/auth-login-illustration-light.png" alt="auth-login-cover" class="img-fluid my-5 auth-illustration" data-app-light-img="illustrations/auth-login-illustration-light.png" data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

                <img src="{{ asset('public/admin') }}/assets/img/illustrations/bg-shape-image-light.png" alt="auth-login-cover" class="platform-bg" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png" />
            </div>
        </div>

        <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
            <div class="w-px-400 mx-auto">
                <div class="app-brand mb-4">
                    <!-- <a href="#" class="app-brand-link gap-2">
                            <img style="width: 100%" src="{{ asset('public/admin/assets/img/logo/black-logo.png') }}" class="img-fluid light-logo" alt="Logo"/>
                        </a> -->

                    @if(isset(settings()->black_logo) && !empty(settings()->black_logo))
                    <img width="250" src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" class="img-fluid light-logo" alt="Logo" />
                    @else
                    <img style="width: 100%" src="{{ asset('public/admin/assets/img/logo/default.png') }}" class="img-fluid light-logo" alt="Logo" />
                    @endif
                </div>
                <h3 class="mb-1 fw-bold">Welcome to @if(isset(settings()->name) && !empty(settings()->name)) {{ settings()->name }} @endif! 👋</h3>
                <p class="mb-4">Please sign-in to your account and start the adventure</p>

                <div id="errorMessage"></div>

                <form id="loginForm" action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <input type="hidden" name="secretKey" id="secretKey">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" autofocus />
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    </div>
                    <div class="mb-3 form-password-toggle">
                        <div class="d-flex justify-content-between">
                            <label class="form-label" for="password">Password</label>
                            <a href="{{ route('password.request') }}">
                                <small>Forgot Password?</small>
                            </a>
                        </div>
                        <div class="input-group input-group-merge">
                            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                            <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember-me" />
                            <label class="form-check-label" for="remember-me"> Remember Me </label>
                        </div>
                    </div>
                    <div class="col-12 mt-3">
                        <span id="login-btn">
                            <button type="submit" id="loginButton" class="btn btn-primary d-grid w-100">Sign in </button>
                        </span>

                        <div id="loader" style="display: none;">
                            <button type="button" class="btn btn-primary w-100" disabled><span class="spinner-border me-1" role="status" aria-hidden="true"></span>Loading...</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script>
    $(document).ready(function() {
        $("#loginButton").click(function(e) {
            e.preventDefault(); // Prevent the default form submission
            var email = $("#email").val();
            var pass = $("#password").val();
            if (email && pass) {
                // Show the loader
                $('#login-btn').hide();
                $("#loader").show();
                // Hide the error message if it's currently displayed
                $("#errorMessage").hide();
                // Perform your AJAX form submission here (e.g., using $.post or $.ajax)

                //var keyname = setKeyName(email, pass);

                // setTimeout(function() {
                    var url = $(this).attr('action');

                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $("#loginForm").serialize(), // Serialize form data
                        success: function(response) {
                            // Check the response for validation errors
                            if (response.success == true) {
                                // If login is successful, you can redirect or perform other actions
                                window.location.href = response.route;
                                // window.location.href = "{{ route('dashboard') }}";
                            } else {
                                // If there are validation errors, hide the loader and display the error message
                                $("#loader").hide();
                                $('#login-btn').show();
                                var html = '<div class="alert alert-danger">' + response.error + '</div>';
                                $("#errorMessage").html(html).show();
                            }
                        },
                        error: function() {
                            // Handle AJAX errors here
                            $("#loader").hide();
                            $('#login-btn').show();
                            var html = '<div class="alert alert-danger">Invalid login credentials</div>';
                            $("#errorMessage").html(html).show();
                        }
                    });


                // }, 200);
            } else {
                var html = '<div class="alert alert-danger">Please insert email and password</div>';
                $("#errorMessage").html(html).show();
            }

        });

        // function setKeyName(email, pass) {
        //     $.ajax({
        //         method: "GET",
        //         url: "{{ route('admin.getKeyName') }}",
        //         data: {
        //             email: email,
        //             pass: pass
        //         },
        //         success: function(res) {
        //             var getsecretKey = localStorage.getItem(res.data);
        //             $("#secretKey").val(getsecretKey);
        //         }
        //     });
        // }
    });
</script>
@endpush