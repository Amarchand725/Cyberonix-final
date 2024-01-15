@extends('admin.auth.layouts.app')
@section('title', 'Cache Cleared')
@section('content')
    <div class="container-xxl container-p-y">
        <div class="misc-wrapper">
            <h2 class="mb-1 mx-2">Cache Cleared!</h2>
            <p class="mb-4 mx-2">You have cleared cache.</p>
            <a href="{{ route('admin.login') }}" class="btn btn-primary mb-4">Go to Dashboard</a>
            <div class="mt-4">
                <img src="{{ asset('public/admin') }}/assets/img/illustrations/page-misc-under-maintenance.png"
                alt="page-misc-under-maintenance"
                width="550"
                class="img-fluid"
                />
            </div>
        </div>
    </div>
    <div class="container-fluid misc-bg-wrapper misc-under-maintenance-bg-wrapper">
        <img src="{{ asset('public/admin') }}/assets/img/illustrations/bg-shape-image-light.png"
        alt="page-misc-under-maintenance"
        data-app-light-img="illustrations/bg-shape-image-light.png"
        data-app-dark-img="illustrations/bg-shape-image-dark.png"
        />
    </div>
@endsection
