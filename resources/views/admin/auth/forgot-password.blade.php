@extends('admin.auth.layouts.app')
@section('title', $title, ' - '. appName())
@section('content')
    <div class="authentication-wrapper authentication-cover authentication-bg">
        <div class="authentication-inner row">
            <div class="d-none d-lg-flex col-lg-7 p-0">
                <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
                    <img src="{{ asset('public/admin') }}/assets/img/illustrations/auth-forgot-password-illustration-light.png"
                      alt="auth-forgot-password-cover"
                      class="img-fluid my-5 auth-illustration"
                      data-app-light-img="illustrations/auth-forgot-password-illustration-light.png"
                      data-app-dark-img="illustrations/auth-forgot-password-illustration-dark.png"
                    />
        
                    <img
                      src="{{ asset('public/admin') }}/assets/img/illustrations/bg-shape-image-light.png"
                      alt="auth-forgot-password-cover"
                      class="platform-bg"
                      data-app-light-img="illustrations/bg-shape-image-light.png"
                      data-app-dark-img="illustrations/bg-shape-image-dark.png"
                    />
                </div>
            </div>

            <!-- Forgot Password -->
            <div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
                <div class="w-px-400 mx-auto">
                    <!-- Logo -->
                    <div class="app-brand mb-4">
                        <a href="#" class="app-brand-link gap-2">
                            @if(isset(settings()->logo) && !empty(settings()->logo))
                                <img style="width: 100%" src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->logo }}" class="img-fluid light-logo" alt="{{ settings()->name }}"/>
                            @else
                                <img style="width: 100%" src="{{ asset('public/admin/default.png') }}" class="img-fluid light-logo" alt="Default"/>
                            @endif
                        </a>
                    </div>
                    <!-- /Logo -->
                    <h3 class="mb-1 fw-bold">Forgot Password? 🔒</h3>
                    <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                              type="email"
                              class="form-control"
                              id="email"
                              name="email"
                              placeholder="Enter your email"
                              autofocus
                            />
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100">Send Reset Link</button>
                    </form>
                    <div class="text-center">
                      <a href="{{ route('admin.login') }}" class="d-flex align-items-center justify-content-center">
                        <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                        Back to login
                      </a>
                    </div>
                </div>
            </div>
            <!-- /Forgot Password -->
        </div>
    </div>
@endsection
