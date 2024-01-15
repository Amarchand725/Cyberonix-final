<!DOCTYPE html>

<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
    <title>{{ $mailData['title'] }}</title>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <link href="https://fonts.googleapis.com/css2?family=Inter&family=Work+Sans:wght@700&display=swap" rel="stylesheet" type="text/css" />
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
        
        @media (max-width:620px) {
            .desktop_hide table.icons-inner {
                display: inline-block !important;
            }
            .icons-inner {
                text-align: center;
            }
            .icons-inner td {
                margin: 0 auto;
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

<body style="text-size-adjust: none; background-color: #f6f6f6; margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f6f6f6;" width="100%">
        <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 600px; margin: 0 auto;" width="600">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; border-top: 6px solid #E30B5C; padding-bottom: 5px; padding-right: 20px; padding-top: 5px; vertical-align: top; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="15" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    @if(isset(settings()->black_logo) && !empty(settings()->black_logo))
                                                                        <img title="{{ settings()->name }}" src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" style="height: auto; display: block; border: 0; max-width: 145px; width: 100%;" width="145" />
                                                                    @else
                                                                        <img title="Default" src="{{ asset('public/admin/default.png') }}" style="height: auto; display: block; border: 0; max-width: 145px; width: 100%;" width="145" />
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
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #f8f7fa;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 600px; margin: 0 auto;" width="600">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 4px; padding-left: 24px; padding-right: 24px; padding-top: 9px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="10" cellspacing="0" class="heading_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <h1 style="margin: 0; color: #e30b5c; direction: ltr; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 21px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: left; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">Dear All, </span></h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:15px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div style="color:#5d596c;direction:ltr;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:left;mso-line-height-alt:28.8px;">
                                                                    <p style="margin: 0;">I am writing to inform you that we have terminated the employment of {{ $mailData['employee'] }} from our organization, effective immediately.<br/><br/>As per company policy, I am notifying you
                                                                        of this termination and providing you with the necessary information for payroll and other administrative purposes. </p>
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
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; width: 600px; margin: 0 auto;" width="600">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-left: 24px; padding-right: 24px; padding-top: 2px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="10" cellspacing="0" class="paragraph_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div style="color:#5d596c;direction:ltr;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:left;mso-line-height-alt:28.8px;">
                                                                    <p style="margin: 0; margin-bottom: 16px;">{{ $mailData['employee'] }} 's final paycheck will be processed and distributed in accordance with state and federal laws.Please note that {{ $mailData['employee'] }} will no longer have access to our organization's
                                                                        portals, systems, and resources, effective immediately. We kindly request that you take the necessary steps to revoke their access and ensure the security of our systems and data.<br/><br/>If
                                                                        you have any questions or concerns regarding this matter, please do not hesitate to contact me. <br/><br/>Thank you for your attention to this matter. <br/></p>
                                                                    <p style="margin: 0;">HR Management, <br/>{{ appName() }}</p>
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
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #f6f6f6; width: 600px; margin: 0 auto;" width="600">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-left: 10px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="33.333333333333336%">
                                                    <table border="0" cellpadding="15" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad">
                                                                <div align="left" class="alignment" style="line-height:10px">
                                                                    @if(isset(settings()->black_logo) && !empty(settings()->black_logo))
                                                                        <img title="{{ settings()->name }}" src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" style="height: auto; display: block; border: 0; max-width: 123.5px; width: 100%;" width="123.5" />
                                                                    @else
                                                                        <img title="Default" src="{{ asset('public/admin/default.png') }}" style="height: auto; display: block; border: 0; max-width: 123.5px; width: 100%;" width="123.5" />
                                                                    @endif
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td class="column column-2" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-right: 20px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="66.66666666666667%">
                                                    <div class="spacer_block block-1" style="height:10px;line-height:10px;font-size:1px;"> </div>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:15px;padding-left:15px;padding-right:15px;padding-top:25px;">
                                                                <div style="color:#6f6b7d;direction:ltr;font-family:Arial, 'Helvetica Neue', Helvetica, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:right;mso-line-height-alt:16.8px;">
                                                                    <p style="margin: 0;">Copyright © 2023 {{ appName() }}, All rights reserved.</p>
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
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>