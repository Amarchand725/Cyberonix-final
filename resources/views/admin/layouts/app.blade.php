<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-menu-collapsed" dir="ltr" data-theme="theme-default" data-assets-path="{{ asset('public/admin') }}/assets/" data-template="vertical-menu-template-no-customizer">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="cache-control" content="post-check=0, pre-check=0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">

    <title>@yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="" />

    <!-- Favicon -->
    @if(checkRocketFlareUser() == 1 && checkRocketFlareUser() != 2)
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin/assets/img/rocketflare/favicon.png') }}" />
    @else
    @if(!empty(settings()))
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/{{ settings()->favicon }}" />
    @else
    <link rel="icon" type="image/x-icon" href="{{ asset('public/admin') }}/assets/img/favicon/favicon.ico" />
    @endif
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/fonts/tabler-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/demo.css') }}" />


    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{asset('public/admin/assets/css/toastr.min.css')}}">

    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/pages/cards-advance.css') }}" />
    <!-- Helpers -->

    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/vendor/css/pages/sweetalert2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('public/admin/assets/vendor/css/pages/ext-component-sweet-alerts.min.css')}}">

    <!-- custom css content -->
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <!--<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- custom css content -->

    <!-- chat css content -->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/chat.css') }}" />
    <!-- chat css content -->

    <script src="{{ asset('public/admin/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/template-customizer.js') }}"></script>

    <script src="{{ asset('public/admin/assets/js/config.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/responsive-xl.css') }}" media="(max-width: 1230px)">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/responsive-lg.css') }}" media="(max-width: 1200px)">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/responsive-md.css') }}" media="(max-width: 992px)">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/responsive-sm.css') }}" media="(max-width: 786px)">
</head>

<body class="position-relative">
    <button class="btn-primary btn-icon scroll-top waves-effect waves-light position-fixed rounded" type="button" id="scrollTop">
        <i class="ti ti-arrow-bar-up"></i>
    </button>
    @if(checkRocketFlareUser() == 1 && checkRocketFlareUser() != 2)
    <input type="hidden" value="{{checkRocketFlareUser() ?? 0 }}">
    <div id="loading-gif-2">

    </div>
    @else
    <div id="loading-gif">
        <svg class="pl" viewBox="0 0 128 128" width="128px" height="128px">
            <defs>
                <linearGradient id="pl-grad" x1="0" y1="0" x2="1" y2="1">
                    <stop offset="0%" stop-color="#000" />
                    <stop offset="100%" stop-color="#fff" />
                </linearGradient>
                <mask id="pl-mask">
                    <rect x="0" y="0" width="128" height="128" fill="url(#pl-grad)" />
                </mask>
            </defs>
            <g stroke-linecap="round" stroke-width="8" stroke-dasharray="32 32">
                <g stroke="hsl(337.5deg 94.61% 49.57%)">
                    <line class="pl__line1" x1="4" y1="48" x2="4" y2="80" />
                    <line class="pl__line2" x1="19" y1="48" x2="19" y2="80" />
                    <line class="pl__line3" x1="34" y1="48" x2="34" y2="80" />
                    <line class="pl__line4" x1="49" y1="48" x2="49" y2="80" />
                    <line class="pl__line5" x1="64" y1="48" x2="64" y2="80" />
                    <g transform="rotate(180,79,64)">
                        <line class="pl__line6" x1="79" y1="48" x2="79" y2="80" />
                    </g>
                    <g transform="rotate(180,94,64)">
                        <line class="pl__line7" x1="94" y1="48" x2="94" y2="80" />
                    </g>
                    <g transform="rotate(180,109,64)">
                        <line class="pl__line8" x1="109" y1="48" x2="109" y2="80" />
                    </g>
                    <g transform="rotate(180,124,64)">
                        <line class="pl__line9" x1="124" y1="48" x2="124" y2="80" />
                    </g>
                </g>
                <g stroke="hsl(337.5deg 90.98% 30.38%)" mask="url(#pl-mask)">
                    <line class="pl__line1" x1="4" y1="48" x2="4" y2="80" />
                    <line class="pl__line2" x1="19" y1="48" x2="19" y2="80" />
                    <line class="pl__line3" x1="34" y1="48" x2="34" y2="80" />
                    <line class="pl__line4" x1="49" y1="48" x2="49" y2="80" />
                    <line class="pl__line5" x1="64" y1="48" x2="64" y2="80" />
                    <g transform="rotate(180,79,64)">
                        <line class="pl__line6" x1="79" y1="48" x2="79" y2="80" />
                    </g>
                    <g transform="rotate(180,94,64)">
                        <line class="pl__line7" x1="94" y1="48" x2="94" y2="80" />
                    </g>
                    <g transform="rotate(180,109,64)">
                        <line class="pl__line8" x1="109" y1="48" x2="109" y2="80" />
                    </g>
                    <g transform="rotate(180,124,64)">
                        <line class="pl__line9" x1="124" y1="48" x2="124" y2="80" />
                    </g>
                </g>
            </g>
            <g stroke="hsl(337.5deg 90.98% 30.38%)" mask="url(#pl-mask)">
                <line class="pl__line1" x1="4" y1="48" x2="4" y2="80" />
                <line class="pl__line2" x1="19" y1="48" x2="19" y2="80" />
                <line class="pl__line3" x1="34" y1="48" x2="34" y2="80" />
                <line class="pl__line4" x1="49" y1="48" x2="49" y2="80" />
                <line class="pl__line5" x1="64" y1="48" x2="64" y2="80" />
                <g transform="rotate(180,79,64)">
                    <line class="pl__line6" x1="79" y1="48" x2="79" y2="80" />
                </g>
                <g transform="rotate(180,94,64)">
                    <line class="pl__line7" x1="94" y1="48" x2="94" y2="80" />
                </g>
                <g transform="rotate(180,109,64)">
                    <line class="pl__line8" x1="109" y1="48" x2="109" y2="80" />
                </g>
                <g transform="rotate(180,124,64)">
                    <line class="pl__line9" x1="124" y1="48" x2="124" y2="80" />
                </g>
            </g>
            </g>
        </svg>
        
    </div>
    @endif
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('admin.layouts.sidebar-menu');
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                @include('admin.layouts.header')
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    @yield('content')

                    <!-- / Content -->

                    <!-- / Content -->

                    <!-- Footer -->
                    @include('admin.layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="{{ asset('public/admin/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <!-- Include Select2 library -->
    <script src="{{ asset('public/admin/assets/js/select2.min.js') }}"></script>
    <!-- Multi date picker to filter summary -->
    <script src="{{ asset('public/admin/assets/js/forms-pickers.js') }}"></script>

    <script src="{{ asset('public/admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('public/admin/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

    <script src="{{ asset('public/admin/assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('public/admin/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('public/admin/assets/js/main.js') }}"></script>

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script> {{-- Getting error if i set it to local path. --}}

    <!-- Page JS -->
    <script src="{{asset('public/admin/assets/js/toastr.min.js')}}"></script>
    <script src="{{asset('public/admin/assets/js/search.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Getting error if i set it to local path. --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <script src="{{ asset('public/admin/assets/js/dashboards-analytics.js') }}" defer></script>

    <script src="{{ asset('public/admin/assets/js/charts-apex.js') }}" defer></script>


    <script src="{{ asset('public/admin/assets/vendor/js/ext-component-sweet-alerts.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/vendor/js/sweetalert2.all.min.js') }}"></script>



    <!-- chat css content -->
    <script src="https://www.gstatic.com/firebasejs/7.13.2/firebase.js"></script>
    <script src="{{ asset('public/admin/assets/js/chat.js') }}"></script>
    <!-- chat css content -->

    <script>
        $(document).ready(function() {
            $("#template-customizer").remove()
            hideLoader()
            //verifyUser()
        })



        // $(document).on('select2:open', () => {
        //     document.querySelector('.select2-search__field').focus();
        // });
        var btn = $('#scrollTop');

        $(window).scroll(function() {
            if ($(window).scrollTop() > 300) {
                btn.addClass('show');
            } else {
                btn.removeClass('show');
            }
        });

        function getThemeMode() {
            return localStorage.getItem("templateCustomizer-vertical-menu-template-no-customizer--Style");
        }
        btn.on('click', function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, '300');
        });

        function hideLoader() {
            $('#loading-gif').hide();
            $('#loading-gif-2').hide();
        }

        function showSuccess(msg) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.success(msg);
        }
          function showSuccessSweet(message) {
                Swal.fire({
                    title: 'Success',
                    text: message,
                    type: 'success',
                  //  showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                  //  cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    confirmButtonClass: 'btn btn-success',
                   // cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    icon: 'success',
                });
            }

            function showWarningSweet(message) {
                Swal.fire({
                    title: 'Processing',
                    text: "Please Wait",
                    type: 'info',
                    showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    icon: 'info',
                });
            }

            function showErrorSweet(message) {
                Swal.fire({
                    title: 'Error',
                    text: message,
                    type: 'error',
                    //showCancelButton: false,
                    confirmButtonColor: '#3085d6',
                    //cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    confirmButtonClass: 'btn btn-danger',
                    //cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                    icon: 'error',
                });
            }

        // error function
        function showError(msg) {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error(msg);
        }

        function getThemeAccordingToAppMode() {
            var theme = getThemeMode();
            if (theme == "light") {
                var theme = "material";
            } else {
                var theme = "dark";
            }
            return theme;
        }

        function showJqueryConfirm(message = null, type = null, theme = null) {
            if (!theme) {
                if (getThemeMode() == "light") {
                    var theme = "material";
                } else {
                    var theme = "dark";
                }
            }
            if (!type) {
                var type = "warnig";
            }

            $.alert({
                title: 'Success!',
                content: message,
                theme: theme,
                type: type,
                typeAnimated: true,
                draggable: true,
                // animation: "rotateY",
            });
        }
        $(window).ready(hideLoader);
        @if(Session::has('message'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.success("{{ session('message') }}");
        @endif

        @if(Session::has('error'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.error("{{ session('error') }}");
        @endif

        @if(Session::has('info'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.info("{{ session('info') }}");
        @endif

        @if(Session::has('warning'))
        toastr.options = {
            "closeButton": true,
            "progressBar": true
        }
        toastr.warning("{{ session('warning') }}");
        @endif

        $(document).on("input", ".numeric", function() {
            this.value = this.value.replace(/\D/g, '');
        });

        // $(document).ready(function() {
        //     $('.form-select').select2();
        // });
        $('.form-select').each(function() {
            $(this).select2({
                dropdownParent: $(this).parent(),
            });
        });
        if (typeof description !== 'undefined') {
            CKEDITOR.replace('description');
        }

        $(document).on('keyup', '.cnic_number', function() {
            var cnic = $(this).val();
            var formattedCnic = formatCnic(cnic);
            $(this).val(formattedCnic);
        });

        function formatCnic(cnic) {
            cnic = cnic.replace(/\D/g, ''); // Remove non-numeric characters
            if (cnic.length > 5) {
                cnic = cnic.substring(0, 5) + "-" + cnic.substring(5, 12) + "-" + cnic.substring(12, 13);
            } else if (cnic.length > 2) {
                cnic = cnic.substring(0, 5) + "-" + cnic.substring(5);
            }
            return cnic;
        }
        $(document).on('keyup', '.mobileNumber', function() {
            var mobile = $(this).val();
            var formattedMobile = formatMobileNumber(mobile);
            $(this).val(formattedMobile);
        });

        function formatMobileNumber(mobile) {
            mobile = mobile.replace(/\D/g, ''); // Remove non-numeric characters
            if (mobile.length > 4) {
                mobile = mobile.substring(0, 4) + "-" + mobile.substring(4, 11);
            }
            return mobile;
        }

        $(document).on('keyup', '.phoneNumber', function() {
            var phone = $(this).val();
            var formattedPhone = formatPhoneNumber(phone);
            $(this).val(formattedPhone);
        });

        function formatPhoneNumber(phone) {
            phone = phone.replace(/\D/g, '');
            if (phone.length > 3) {
                var areaCode = phone.substring(0, 3);
                var telephoneNumber = phone.substring(3, 11);
                phone = "(" + areaCode + ") - " + telephoneNumber;
            }
            return phone;
        }

        $('.read-all-notifications').on('click', function() {
            $.ajax({
                url: "{{ route('notifications.mark-as-read') }}",
                type: 'GET',
                success: function(response) {
                    $('.read-badge-notification').text(response);
                    location.reload();
                }
            });
        });

      function verifyUser() {
            var token = localStorage.getItem("secretKey");
            if(token) {
                $.ajax({
                    url: "{{ route('admin.verifyUserToken') }}",
                    type: 'GET',
                    data: {
                        token: token,
                    },
                    success: function(res) {
                        if (res.success == false){
                            localStorage.removeItem("secretKey");
                             // remoive local storage key
                             // session expire
                             showErrorSweet(res.error)
                            window.location.href = res.route;
                        }
                    }
                });
            } else {
                window.location.href = "{{ route('admin.login') }}";
            }

      }
        setTimeout(() => {

            // console.log($('table').parent());
            $('table.dataTable').parent().addClass('table-responsive');
        }, 1000);


        // $(document).ready(function() {
        //     var token = localStorage.getItem("secretKey");
        //     console.log(token)
        //     $.ajax({
        //         url: "{{ route('admin.verifyUserToken') }}",
        //         type: 'GET',
        //         data: {
        //             token: token,
        //         },
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });
        // })
    </script>

    <!-- custom js content -->
    @stack('js')
    <!-- custom js content -->
</body>

</html>
