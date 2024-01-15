@extends('admin.auth.layouts.app')
@section('title', 'RESTRICTED! 🔐')
@section('content')
<!-- Not Authorized -->
<div class="container-xxl container-p-y">
    <div class="misc-wrapper">
        <h2 class="mb-1 mx-2">RESTRICTED! 🔐</h2>
        <p class="mb-4 mx-2">
            The IP you are using is currently restricted by Administrator <br />
        </p>
        {{-- <a href="{{ route('admin.login') }}" class="btn btn-primary mb-4">Back to home</a> --}}
        <div class="mt-4">
            <img src="{{ asset('public/admin') }}/assets/img/illustrations/page-misc-you-are-not-authorized.png" alt="page-misc-not-authorized" width="170" class="img-fluid" />
        </div>
    </div>
</div>
<div class="container-fluid misc-bg-wrapper">
    <img src="{{ asset('public/admin') }}/assets/img/illustrations/bg-shape-image-light.png" alt="page-misc-not-authorized" data-app-light-img="illustrations/bg-shape-image-light.png" data-app-dark-img="illustrations/bg-shape-image-dark.png" />
</div>
<!-- /Not Authorized -->
@endsection