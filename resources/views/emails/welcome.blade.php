<!DOCTYPE html>

<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <title>{{ $mailData['title'] }}</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
        }

        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
        }

        p {
            line-height: inherit
        }

        .desktop_hide,
        .desktop_hide table {
            mso-hide: all;
            display: none;
            max-height: 0px;
            overflow: hidden;
        }

        .image_block img+div {
            display: none;
        }

        @media (max-width:720px) {
            .desktop_hide table.icons-inner {
                display: inline-block !important;
            }
            .icons-inner {
                text-align: center;
            }
            .icons-inner td {
                margin: 0 auto;
            }
            .image_block img.fullWidth {
                max-width: 100% !important;
            }
            .social_block.desktop_hide .social-table {
                display: inline-block !important;
            }
            .row-content {
                width: 100% !important;
            }
            .stack .column {
                width: 100%;
                display: block;
            }
            .mobile_hide {
                max-width: 0;
                min-height: 0;
                max-height: 0;
                font-size: 0;
                display: none;
                overflow: hidden;
            }
            .desktop_hide,
            .desktop_hide table {
                max-height: none !important;
                display: table !important;
            }
        }
    </style>
</head>

<body style="text-size-adjust: none; background-color: #f9f9f9; margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f9f9f9;" width="100%">
        <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <div class="spacer_block block-1" style="height:10px;line-height:10px;font-size:1px;"> </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:10px;width:100%;">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    @if(isset(settings()->black_logo) && !empty(settings()->black_logo))
                                                                        <img title="{{ settings()->name }}" src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" style="height: auto; display: block; border: 0; max-width: 154px; width: 100%;" width="154" />
                                                                    @else
                                                                        <img title="Default" src="{{ asset('public/admin/default.png') }}" style="height: auto; display: block; border: 0; max-width: 154px; width: 100%;" width="154" />
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #ffd3e0; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <div class="spacer_block block-1" style="height:30px;line-height:30px;font-size:1px;"> </div>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block block-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    <img alt="I'm an image" class="fullWidth" src="{{ asset('public/admin/email-temp-assets/Welcome_Email.png') }}" style="height: auto; display: block; border: 0; max-width: 420px; width: 100%;" title="I'm an image" width="420" /></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:10px;padding-left:40px;padding-right:40px;padding-top:10px;">
                                                                <div style="color:#191919;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:38px;line-height:150%;text-align:center;mso-line-height-alt:57px;">
                                                                    <p style="margin: 0; word-break: break-word;"><strong><span>Hi {{ $mailData['employee_info']['name'] }}, </span></strong></p>
                                                                    <p style="margin: 0; word-break: break-word;"><strong><span>Welcome to our organization!</span></strong></p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <div class="spacer_block block-4" style="height:55px;line-height:55px;font-size:1px;"> </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <div class="spacer_block block-1" style="height:25px;line-height:25px;font-size:1px;"> </div>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-left:20px;padding-right:20px;padding-top:10px;">
                                                                <div style="color:#191919;font-family:Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif;font-size:17px;line-height:150%;text-align:left;mso-line-height-alt:25.5px;">
                                                                    <p style="margin: 0; word-break: break-word;">Welcome to {{ appName() }}! We are delighted to have you join our team and want to ensure your onboarding process is smooth and hassle-free. To get you started, we have provided important information below:.<br/><br/>
                                                                        Employee Portal Access:<br/><br/>You can access our comprehensive and user-friendly employee portal, where you will find valuable resources, company updates, and have access to various features such as viewing your attendance records, checking salary details, and applying for leave. To log in, please click on the following link:<br />
                                                                        Employee Portal: {{ route('admin.login') }} <br /><br />
                                                                        Please log in using the credentials provided below:<br /><br />
                                                                        Email: {{ $mailData['employee_info']['email'] }} <br />
                                                                        Password: {{ $mailData['employee_info']['password'] }} <br /><br />
                                                                        Bank Details Form:<br />
                                                                        As part of our payroll processing, we kindly request you to fill in your bank details. This information is essential to ensure timely salary transfers. Please click on the link below to access the secure bank details form:<br /><br />

                                                                        Rest assured that both the employee portal and the bank details form are secure, and all the information you provide will be treated with the utmost confidentiality.
                                                                        <br/><br/>We kindly ask you to complete the bank details form as soon as possible to avoid any delays in salary processing. If you encounter any difficulties during the process or have any questions, please don't hesitate to reach out to our HR department for assistance.
                                                                        <br/><br/>Once again, welcome to our team! We are excited about the contributions you will bring to our organization's success. If you have any further queries or need additional information, feel free to get in touch with us.<br/><br/></p>
                                                                        <p style="margin: 0;">Best regards,, </p> <br /><br />
                                                                        <p style="margin: 0;">HR Management, </p>
                                                                        <p style="margin: 0;">{{ appName() }}</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider_block block-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="5%">
                                                                        <tr>
                                                                            <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 2px solid #FFD3E0;"><span> </span></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-6" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;"> </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-8" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <div class="spacer_block block-1" style="height:20px;line-height:20px;font-size:1px;"> </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-9" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="width:100%;">
                                                                <div align="center" class="alignment" style="line-height:10px"><img alt="Alternate text" src="{{ asset('public/admin/email-temp-assets/Up_pink.png') }}" style="height: auto; display: block; border: 0; max-width: 700px; width: 100%;" title="Alternate text" width="700" /></div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-10" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffd3e0; background-position: top center; color: #000; width: 700px; margin: 0 auto;"
                                        width="650">
                                        <tbody>
                                            @if(!empty(settings()->facebook_link) || !empty(settings()->linked_in_link) || !empty(settings()->instagram_link))
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="text_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div style="font-family: sans-serif">
                                                                    <div class="" style="font-size: 12px; font-family: Montserrat, Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2;">
                                                                        <p style="margin: 0; font-size: 12px; text-align: center; mso-line-height-alt: 14.399999999999999px;"><span style="font-size:18px;">Follow us</span></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="social_block block-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="text-align:center;padding-right:0px;padding-left:0px;">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0" class="social-table" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block;">
                                                                        <tr>
                                                                            @if(!empty(settings()->facebook_link))
                                                                                <td style="padding:0 5px 0 5px;">
                                                                                    <a href="{{ settings()->facebook_link }}" target="_blank">
                                                                                        <img alt="{{ appName() }}" height="32" src="{{ asset('public/admin/email-temp-assets/facebook2x.png') }}" style="display: block; height: auto; border: 0;" title="{{ appName() }}" width="32" />
                                                                                    </a>
                                                                                </td>
                                                                            @endif
                                                                            @if(!empty(settings()->linked_in_link))
                                                                                <td style="padding:0 5px 0 5px;">
                                                                                    <a href="{{ settings()->linked_in_link }}" target="_blank">
                                                                                        <img alt="{{ appName() }}" height="32" src="{{ asset('public/admin/email-temp-assets/linkedin2x.png') }}" style="display: block; height: auto; border: 0;" title="{{ appName() }}" width="32" />
                                                                                    </a>
                                                                                </td>
                                                                            @endif
                                                                            @if(!empty(settings()->instagram_link))
                                                                                <td style="padding:0 5px 0 5px;">
                                                                                    <a href="{{ settings()->linked_in_link }}" target="_blank">
                                                                                        <img alt="{{ appName() }}" height="32" src="{{ asset('public/admin/email-temp-assets/instagram2x.png') }}" style="display: block; height: auto; border: 0;" title="{{ appName() }}" width="32" />
                                                                                    </a>
                                                                                </td>
                                                                            @endif
                                                                            @if(!empty(settings()->twitter_link))
                                                                                <td style="padding:0 5px 0 5px;">
                                                                                    <a href="{{ settings()->twitter_link }}" target="_blank">
                                                                                        <img alt="{{ appName() }}" height="32" src="{{ asset('public/admin/email-temp-assets/twitter.png') }}" style="display: block; height: auto; border: 0;" title="{{ appName() }}" width="32" />
                                                                                    </a>
                                                                                </td>
                                                                            @endif
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="divider_block block-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:20px;">
                                                                <div align="center" class="alignment">
                                                                    <table border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="65%">
                                                                        <tr>
                                                                            <td class="divider_inner" style="font-size: 1px; line-height: 1px; border-top: 0px dotted #FFFFFF;"><span> </span></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-11" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <div class="spacer_block block-1" style="height:25px;line-height:25px;font-size:1px;"> </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-12" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="icons_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="vertical-align: middle; color: #9d9d9d; font-family: inherit; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;">
                                                                <table cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                                    <tr>
                                                                        <td class="alignment" style="vertical-align: middle; text-align: center;">
                                                                            <table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
