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
                    <h3 class="mb-1 fw-bold">Reset Password 🔒</h3>
                    <p class="mb-4">for <span class="fw-bold">{{ $request->email }}</span></p>
                    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.store') }}">
                        @csrf
                        
                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <input type="hidden" name="email" value="{{ $request->email }}">
                        
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password">New Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                type="password"
                                id="password"
                                class="form-control"
                                name="password"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password"
                              />
                              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <div class="mb-3 form-password-toggle">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <div class="input-group input-group-merge">
                              <input
                                type="password"
                                id="password_confirmation"
                                class="form-control"
                                name="password_confirmation"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                aria-describedby="password"
                              />
                              <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary d-grid w-100 mb-3">Set new password</button>
                        <div class="text-center">
                            <a href="{{ route('admin.login') }}">
                              <i class="ti ti-chevron-left scaleX-n1-rtl"></i>
                              Back to login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
