<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <title></title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
      
        <link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Inter&family=Work+Sans:wght@700&display=swap" rel="stylesheet" type="text/css"/>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700;900&display=swap" rel="stylesheet" type="text/css"/>
        <style>
            @media print {
                * {
                    -webkit-print-color-adjust: exact !important; /*Chrome, Safari */
                    color-adjust: exact !important;  /*Firefox*/
                }
                body {
                    margin: 0;
                    padding: 0;
                }
            
                .pdf-header {
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 100px; /* Set header height */
                }
            
                .custom-page-start{
                    margin-top: 60px; /* Adjust based on header height */
                    margin-bottom: 40px; /* Adjust based on footer height */
                }
            
                .pdf-footer {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 100px; /* Set footer height */
                }
            }
            @page {
                margin: 0cm;
                size: A3;
            }
            .pdfcontent {
                background: url('public/admin/letters/middle-Icon.png');
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
            }
            .header-left{
                width:50%;
                float:left;
            }
            .header-right {
                width: 50%;
                float: right;
                text-align: right;
                padding-right: 40px;
            }
            .pdfcontent-section.pdfcontent-section .date {
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
                font-size: 17px;
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
            .footer-left{
                width:50%;
                float:left;
            }
            .footer-right{
                width:50%;
                float:right;
                display: flex;
                justify-content: end;
            }
            .pdf-footer ul li{
                font-size: 14px;
                color: #000000;
                vertical-align: middle;
                letter-spacing: undefined;
                text-align: left;
                margin-bottom: 40px;
            }
            .pdfcontent-section p span {
                font-weight: bold;
            }
            .pdfcontent-section .name {
                margin: 0;
                color: #201f42;
                font-size: 25px;
                font-weight: 700;
                text-align: center;
                text-decoration: underline;
                margin-bottom: 43px;
            }
            .pdfcontent h6{
                font-weight: 700; 
            }
            .pdf-header .logo{
                max-width: 265px;
                width: 100%;
                margin-top: 10px;
            }
            .pdf-header .top-particle{
                width: 70%;
            }
            .pdf-footer .bottom-particle{
                width: 80%;
            }
    	</style>
    </head>
    <body>
        <header class="pdf-header">
            <div class="row">
                <div class="header-left">
                    <img src="{{ asset('public/admin/letters/Top.png') }}" class="top-particle img-fluid"/>
                </div>
                <div class="header-right">
                    @if(!empty(settings()->black_logo))
                        <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" alt="{{ settings()->name }}" class="logo img-fluid" />
                    @else
                        <img src="{{ asset('public/admin/default.png') }}" style="width:150px" class="logo img-fluid" title="Company Black Logo Here..." alt="Default"/>
                    @endif
                </div>
            </div>
        </header>
        <div class="custom-page-start px-5">
            <section class="pdfcontent-section px-4">
                <div class="row">
                    <div class="col-12 mt-5">
                        <h6 class="date mt-5">Date: {{ $model->effective_date }},</h6>
                        <h4 class="mt-5 name">COMPANY’S RENTAL VEHICLE AUTHORIZATION LETTER</h4>
                        <div class="pdfcontent mt-3 position-relative">
                            <p>
                                We are delighted to inform you that, based on your eligibility and in accordance with our company policies, <span>{{ appName() }} (SMC - Private Limited)</span> has allocated you a company vehicle for personal use. This provision aims to improve your productivity, convenience, and cater to your personal transportation needs.</p>
                            <p>We hereby authorize <span>{{ $model->name }}</span>, bearing <span>CNIC # {{ $model->cnic }}</span>, to utilize the company car, <span>{{ $model->vehicle_name }}</span> with the registration number <span>{{ $model->vehicle_reg_number }}</span>, for personal purposes. It is crucial to understand that any accidents or damages resulting from negligence or improper use may hold you financially liable. Therefore, please exercise utmost care and responsibility while using the vehicle. Please be aware that any misconduct or inappropriate use will be the responsibility of the authorized user and not the company.

                                Feel free to reach on the mentioned below contact details in case of any queries. We appreciate your cooperation in adhering to the guidelines provided.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="pdfsign-section px-4 mt-2">
                <div class="row">
                    <div class="col-12">
                        
                        <h6 class="mt-2"> Sincerely, </h6>
                        @if(!empty(settings()->admin_signature))
                            <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->admin_signature }}" alt="Signature" />
                        @endif
                        <h6>Administration Department</h6>
                    </div>
                </div>
            </section>
        </div>
        <footer class="mt-5 pdf-footer">
            <div class="row">
                <div class="footer-left">
                    <ul class="list-unstyled ps-4">
                        @if(!empty(settings()->phone_number))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Call.png') }}" class="img-fluid" alt="Number Icon" />
                            {{ settings()->phone_number }}
                        </li>
                        @endif
                        @if(!empty(settings()->website_url))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Website.png') }}" class="img-fluid" alt="Website Icon" />
                            {{ settings()->website_url }}
                        </li>
                        @endif
                        @if(!empty(settings()->email))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Email.png') }}" class="img-fluid" alt="Email Icon" />
                            {{ settings()->email }}
                        </li>
                        @endif
                        @if(!empty(settings()->address))
                        <li class="mb-1">
                            <img class="me-2" src="{{ asset('public/admin/letters/Address.png') }}" class="img-fluid" alt="Address Icon" />
                            {{ settings()->address }}
                        </li>
                        @endif
                    </ul>
                </div>
                <div class="footer-right">
                    <img src="{{ asset('public/admin/letters/Bottom.png') }}" class="bottom-particle img-fluid"/>
                </div>
            </div>
        </footer>
    </body>
</html>