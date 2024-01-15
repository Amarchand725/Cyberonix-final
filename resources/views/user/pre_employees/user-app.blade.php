
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title')</title>

    @if(!empty(settings()))
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ settings()->favicon }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/favicon.ico" />
    @endif

    <!--fav icon -->
    <link rel="shortcut icon" href="https://hr.cyberonix.co/storage/icon/64111a2849fc6.png"/>

	<!-- bootstrap css -->
	<link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/bootstrap.min.css') }}">

	<!-- Font awesome 6 -->
	<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v6.1.1/css/all.css">

	<!-- custom styles -->
	<link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/style.css?ver=1.1') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/responsive.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/pre-employees/css/animation.css') }}">

	<!-- color sceme -->
	<link rel="stylesheet" href="{{ asset('public/pre-employees/css/default.css') }}" id="defaultscheme">

</head>

<body>
	<input type="hidden" id="base_url" value="{{ asset('public/pre-employees/images/left-bg.gif') }}">
	<main class="overflow-hidden">
		<div class="logo">
            @if(isset(settings()->black_logo) && !empty(settings()->black_logo))
                <img title="{{ settings()->name }}" src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" class="img-fluid logo">
            @else
                <img title="Default" src="{{ asset('public/admin/default.png') }}" class="img-fluid logo">
            @endif
		</div>
		<div class="container">
			<div class="row h-100">
				<!-- side area -->
				<div class="slideup side col-md-5 order-c">
					<div class="side-inner side-text">

						<h2>
							Time to forge your career, together
						</h2>
						<img src="{{ asset('public/pre-employees/images/left-bg.gif') }}" alt="side image">
					</div>
				</div>
				<div class="slidedown col-md-7 h-100">
					<div class="wrapper">
						@yield('content')
					</div>
				</div>
			</div>
		</div>
		<div class="left-shape">
			<img src="{{ asset('public/pre-employees/images/top-left.png') }}" alt="">
		</div>
		<div class="right-shape">
			<img src="{{ asset('public/pre-employees/images/top-right.png') }}" alt="">
		</div>
	</main>

	<!-- bootstrap JS -->
	<script src="{{ asset('public/pre-employees/js/bootstrap.min.js') }}"></script>

	<!-- Jquery -->
	<script src="{{ asset('public/pre-employees/js/jquery.min.js') }}"></script>

	<!-- custom JS -->
	<script src="{{ asset('public/pre-employees/js/custom.js?ver=3.4') }}"></script>

	<!-- mask -->
	<script src="{{ asset('public/pre-employees/js/input-mask.js') }}"></script>

	@stack('scripts')

	{{-- <script>
		$(":input").inputmask();
		$('#CNIC_No').mask("99999-9999999-9");
	</script> --}}
</body>

</html>
