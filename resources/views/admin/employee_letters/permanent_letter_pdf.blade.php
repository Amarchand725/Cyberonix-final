<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>TITLE</title>
        <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ asset('public/admin/assets/vendor/css/rtl/theme-default.css') }}" class="template-customizer-theme-css" />
        <style>
            * {
                -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
                color-adjust: exact !important;  /*Firefox*/
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 100;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-Thin.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 200;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-ExtraLight.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 300;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-Light.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 400;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-Regular.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 500;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-Medium.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 600;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-Bold.ttf')  format('ttf');
            }
            @font-face {
                font-family: 'Montserrat';
                font-style: normal;
                font-weight: 700;
                src: url('public/admin/assets/vendor/fonts/montserrat/Montserrat-SemiBold.ttf')  format('ttf');
            }
            body {
                margin: 0;
                padding: 0;
                font-family: 'Montserrat', sans-serif !important;
            }
        
            footer{
                position: fixed;
                bottom: 70px;
                left: 30px;
                right: 0px;
                height: 100px; /* Set header height */
            }
            @page {
                margin: 0cm;
                size: A3;
            }
            .pdfcontent {
                background: url('admin/letters/middle-Icon.png');
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
            }
            .left{
                width:50%;
                float:left;
            }
            .right {
                width: 50%;
                float: right;
                text-align: right;
            }
            .pdfcontent-section .date {
                color: #5d596c;
                font-weight: 700;
                text-align: right;
                font-size: 12px;
            }
            .pdfcontent-section .letter-heading {
                border-bottom: 2px solid #000;
                display: table;
                color: #201f42;
                font-weight: 700;
            }
            .pdfcontent-section p {
                color: #201f42;
                direction: ltr;
                font-size: 21px;
                line-height: 180%;
            }
            .pdfsign-section h6{
                color: #000000;
                font-size: 14px;
                font-weight: 400;
            }
            .pdfsign-section img{
                height: auto;
                display: block;
                border: 0;
                max-width: 140px;
                width: 100%;
            }
            .pdf-footer ul li{
                font-size: 14px;
                color: #000000;
                text-align: left;
                margin-bottom:10px;
            }
            .pdfcontent-section p span {
                font-weight: bold;
            }
            .pdfcontent-section .name{
                margin-top: 50px;
                color: #201f42;
                font-size: 28px;
                font-weight: 700;
                letter-spacing: normal;
                
            }
            .pdfcontent-section .name span{
               border-bottom: 2px solid #000; 
            }
            .pdf-header .logo{
                max-width: 265px;
                width: 100%;
                padding-right: 40px;
                margin-top: 10px;
            }
            .left .top-particle{
                width: 50%;
            }
            .right .bottom-particle{
                width: 50%;
            }
             @media print {
                   body {
                    font-family: 'Montserrat', sans-serif !important;
                }
            }
    	</style>
    </head>

        <body>
        <header class="pdf-header">
            <div class="row">
                <div class="left">
                    <img src="{{ asset('public/admin/letters/Top.png') }}" class="top-particle"/>
                </div>
                <div class="right">
                    @if(!empty(settings()->black_logo))
                        <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" class="logo" />
                    @else
                        <img src="{{ asset('public/admin/default.png') }}" style="width:150px" class="logo" title="Company Black Logo Here..." alt="Default"/>
                    @endif
                </div>
            </div>
        </header>
        <div class="custom-page-start px-5">
            <section class="pdfcontent-section px-4">
                <div class="row">
                    <div class="col-12 mt-5">
                        <h6 class="date mt-5">Date: date</h6>
                        <h4 class="mt-5 name"><br/><br/><br/>Dear employee name,</h4>
                        <div class="pdfcontent mt-3 position-relative">
                            <p>
                                I hope this email finds you well. I am writing to inform you about an important update regarding your employment. We are pleased to announce that your hard work, dedication, and valuable contributions to the company have been recognized.
                                After careful consideration, we have decided to permanent. You have been permanent employees in this company regards outstanding performance, commitment, and the value you bring to our organization.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pdfsign-section px-4 mt-2">
                <div class="row">
                    <div class="col-12">
                        @if(!empty(settings()->admin_signature))
                            <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->admin_signature }}" alt="Signature" />
                        @endif
                        <h6 class="mt-2"> Sincerely, </h6>
                        <h6>{{ hrName() }}</h6>
                        <h6>Executive – HR</h6>
                        <h6>{{ appName() }}</h6>
                    </div>
                </div>
            </section>
        </div>
        <footer>
            <div class="row">
                <div class="left">
                    <ul class="list-unstyled">
                        @if(!empty(settings()->phone_number))
                        <li>
                            <img class="me-2" src="{{ asset('public/admin/letters/Call.png') }}" alt="Number Icon" />
                            {{ settings()->phone_number }}<br/><br/>
                        </li>
                        @endif
                        @if(!empty(settings()->website_url))
                        <li>
                            <img class="me-2" src="{{ asset('public/admin/letters/Website.png') }}" alt="Website Icon" />
                            {{ settings()->website_url }}<br/><br/>
                        </li>
                        @endif
                        @if(!empty(settings()->email))
                        <li>
                            <img class="me-2" src="{{ asset('public/admin/letters/Email.png') }}" alt="Email Icon" />
                            {{ settings()->email }}<br/><br/>
                        </li>
                        @endif
                        @if(!empty(settings()->address))
                        <li>
                            <img class="me-2" src="{{ asset('public/admin/letters/Address.png') }}" alt="Address Icon" />
                            {{ settings()->address }}
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="right">
                    <img src="{{ asset('public/admin/letters/Bottom.png') }}" class="bottom-particle" />
                </div>
            </div>
        </footer>
    </body>
</html>
