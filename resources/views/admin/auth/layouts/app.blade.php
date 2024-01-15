<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('public/admin') }}/assets/"
  data-template="vertical-menu-template-no-customizer"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>@yield('title')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    @if(isset(settings()->favicon) && !empty(settings()->favicon))
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ settings()->favicon }}" />
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/favicon.ico" />
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/pages/page-auth.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin') }}/assets/vendor/css/pages/page-misc.css" />
    
    @stack('css')
    
    <!-- Helpers -->
    <script src="{{ asset('public/admin/assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('public/admin/assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Content -->
    @yield('content')
    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="{{ asset('public/admin') }}/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="{{ asset('public/admin') }}/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="{{ asset('public/admin') }}/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('public/admin') }}/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="{{ asset('public/admin') }}/assets/js/pages-auth.js"></script>
    @stack('js')
    <script type="text/javascript">
        $(document).on('click','i[class^="ti ti-eye"]',function(){
           var getType=$(this).parent().parent().find('input').attr('type');
           if(getType=='text'){
               $(this).attr('class','ti ti-eye-off');
               $(this).parent().parent().find('input').attr('type','password');
           }else{
               $(this).attr('class','ti ti-eye');
               $(this).parent().parent().find('input').attr('type','text');
           }
        });
    </script>
  </body>
</html>
