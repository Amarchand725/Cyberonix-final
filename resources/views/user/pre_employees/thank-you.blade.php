<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thank You </title>

    @if(!empty(settings()))
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ settings()->favicon }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/favicon.ico" />
    @endif

    <!-- bootstrap css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/bootstrap.min.css') }}">

    <!-- Font awesome 6 -->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/all.css') }}"> --}}

    <!-- custom styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/style.css?ver=1.1') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/animation.css') }}">
</head>

<body class="show-section">
    <section class="thankyou-page">
        <div class="container">
            <img class="thankyou-part" src="{{ asset('public/pre-employees/images/partical_3.png') }}" alt="">
            <img class="thankyou-part type-2" src="{{ asset('public/pre-employees/images/partical_4.png') }}" alt="">
            <div class="thankyouinner">
                <div class="thumb-image">
                    <img src="{{ asset('public/pre-employees/images/thankyou.png') }}" alt="">
                </div>
                <div class="thankyou-caption">
                    <h2>Thank You</h2>
                    <span>Your submission has been received</span>
                </div>
            </div>
        </div>
        <div class="bg-partical-1">
            <img src="{{ asset('public/pre-employees/images/partical_1.png') }}" alt="partical">
        </div>
        <div class="bg-partical-2">
            <img src="{{ asset('public/pre-employees/images/partical_2.png') }}" alt="Partical">
        </div>
    </section>

    <!-- bootstrap JS -->
    <script src="{{ asset('public/pre-employees/js/bootstrap.min.js') }}"></script>

    <!-- Jquery -->
    <script src="{{ asset('public/pre-employees/js/jquery.min.js') }}"></script>

    <!-- custom JS -->
    <script src="{{ asset('public/pre-employees/js/custom.js') }}"></script>
</body>
</html>
