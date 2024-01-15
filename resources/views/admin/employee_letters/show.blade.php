<!DOCTYPE html>
<html lang="en" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:v="urn:schemas-microsoft-com:vml">
    <head>
        <title>{{ $model->title }}</title>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
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
    			.desktop_hide table {column column-1
    				max-height: none !important;
    				display: table !important;
    			}
    		}
    	</style>
    </head>
    <body style="text-size-adjust: none; background-color: #ffffff; margin: 0; padding: 0;">
        <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;" width="100%">
        <tbody>
            <tr>
                <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; border-radius: 0; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="50%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    <img src="{{ asset('public/admin/letters/Top.png') }}" style="height: auto; display: block; border: 0; max-width: 350px; width: 100%;" width="350" />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td class="column column-2" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="50%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="width:100%;">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    @if(!empty(settings()->black_logo))
                                                                        <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->black_logo }}" style="height: auto; display: block; border: 0; max-width: 184px; width: 100%;" width="184" />
                                                                    @else
                                                                        <img src="{{ asset('public/admin/default.png') }}" style="height: auto; display: block; border: 0; max-width: 184px; width: 100%;" width="184" />
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

                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; border: 0 solid #efeef4; width: 700px; margin: 0 auto;"
                                        width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 15px; padding-left: 25px; padding-right: 25px; padding-top: 35px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="heading_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-top:10px;text-align:center;width:100%;">
                                                                <h1 style="margin: 0; color: #5d596c; direction: ltr; font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 15px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: right; margin-top: 0; margin-bottom: 0;"> Date: {{ $model->effective_date }}<br/></h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="heading_block block-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:20px;padding-top:10px;text-align:center;width:100%;">
                                                                <h2 style="margin: 0; color: #201f42; direction: ltr; font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 19px; font-weight: 700; letter-spacing: normal; line-height: 120%; text-align: left; margin-top: 0; margin-bottom: 0;"><span class="tinyMce-placeholder">Dear {{ $model->name }},</span></h2>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-left:5px;padding-right:10px;">
                                                                <div style="color:#201f42;direction:ltr;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:left;mso-line-height-alt:28.8px;">
                                                                    <p style="margin: 0; margin-bottom: 0px;">Further to your interview, we are pleased to offer you employment at <strong>{{ appName() }}</strong> as a <strong>{{ $model->designation }} – {{ $model->department }}.</strong> We believe that your
                                                                        skills, experience and enthusiasm will make you a valuable member of our team.<br/><br/></p>
                                                                    <p style="margin: 0;">The terms and conditions of your employment are as follows: -</p>
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
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-size: auto;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; background-image: url(images/middle-Icon.png); background-repeat: no-repeat; background-size: cover; border-radius: 0; width: 700px; margin: 0 auto;"
                                        width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:30px;padding-left:35px;padding-right:15px;padding-top:10px;">
                                                                <div style="color:#000000;direction:ltr;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:left;mso-line-height-alt:28.8px;">
                                                                    <p style="margin: 0; margin-bottom: 16px;"><strong>Compensation and Benefits:<br/></strong>Your starting salary will be <strong>PKR {{ $model->salary }}/= ({{ ucwords($model->salary_in_words) }} Only) </strong>per month. In addition to this, you will be entitled
                                                                        to 24 paid leaves annually (after confirmation) and EOBI. Any other regular employee benefits will be announced by the company as and when applicable.<br/><br/></p>
                                                                    <p style="margin: 0; margin-bottom: 16px;"><strong>Probation and Notice Period: <br/></strong>After appointment, your probationary period shall be of 3 months, during which the appointment may be terminated by either you or the
                                                                        company on one week's notice period. After the probationary period, one month's notice period shall be required from both parties, and payment made in lieu thereof.<br/><br/></p>
                                                                    <p style="margin: 0; margin-bottom: 16px;"><strong>Reporting Relationship: </strong><strong><br/></strong>You shall report to <strong>{{ $model->reporting_name }} – {{ $model->reporting_designation }} ({{ $model->reporting_department }}) </strong>or any other person designated by
                                                                        him.<br/><br/><strong>Confidentiality: </strong><br/>You shall not disclose any information about our business to anybody except to the person(s) having such authority to discuss
                                                                        such information.<br/><br/></p>
                                                                    <p style="margin: 0;"><strong>Validity of This Offer: </strong><br/>This employment offer is valid till {{ $model->validity_date }}. The validity of this letter is dependent on the originality of the documents that you
                                                                        will be submitting.<br/><br/>We are excited to have you join our team and look forward to working with you. Furthermore, it is important that you submit all the required documents
                                                                        mentioned in the list provided along with your offer letter in a timely manner to ensure that your appointment letter and payroll processing are not delayed. If you have any questions,
                                                                        please do not hesitate to contact us.</p>
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

                    @if(!empty(settings()->admin_signature))
                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-5" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                            <tbody>
                                <tr>
                                    <td>
                                        <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; border-radius: 0; width: 700px; margin: 0 auto;" width="700">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                        <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                            <tr>
                                                                <td class="pad" style="width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div align="left" class="alignment" style="line-height:10px">
                                                                        <img src="{{ asset('public/admin/assets/img/logo') }}/{{ settings()->admin_signature }}" style="height: auto; display: block; border: 0; max-width: 140px; width: 100%;" width="140" />
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
                    @endif
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-6" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; border-radius: 0; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="paragraph_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-bottom:10px;padding-left:35px;padding-right:10px;">
                                                                <div style="color:#000000;direction:ltr;font-family:'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:180%;text-align:left;mso-line-height-alt:25.2px;">
                                                                    <p style="margin: 0;">Sincerely, <br/>{{ hrName() }}<br/>Executive – HR<br/>{{ appName() }}</p>
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
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-7" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                        <tbody>
                            <tr>
                                <td>
                                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000; background-color: #fff; border-radius: 0; width: 700px; margin: 0 auto;" width="700">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-left: 25px; padding-top: 35px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="50%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="icons_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="vertical-align: middle; color: #000000; font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 14px; font-weight: 400; text-align: left;">
                                                                <table cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                                    <tr>
                                                                        <td class="alignment" style="vertical-align: middle; text-align: left;">
                                                                            <table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <tr>
                                                                                    <td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 10px;">
                                                                                        @php $company_phone_number = '' @endphp
                                                                                        @if(!empty(settings()->phone_number))
                                                                                            @php $company_phone_number = settings()->phone_number; @endphp
                                                                                        @endif
                                                                                        <img align="center" alt="{{ $company_phone_number }}" class="icon" height="16" src="{{ asset('public/admin/letters') }}/Call.png" style="height: auto; display: block; margin: 0 auto; border: 0;" width="16" />
                                                                                    </td>
                                                                                    <td style="font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 14px; color: #000000; vertical-align: middle; letter-spacing: undefined; text-align: left;">{{ $company_phone_number }}</td>
                                                                                </tr>
                                                                            </table>
                                                                            <table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <tr>
                                                                                    <td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 10px;">
                                                                                        @php $company_website_url = '' @endphp
                                                                                        @if(!empty(settings()->website_url))
                                                                                            @php $company_website_url = settings()->website_url; @endphp
                                                                                        @endif
                                                                                        <img align="center" alt="{{ $company_website_url }}" class="icon" height="16" src="{{ asset('public/admin/letters') }}/Website.png" style="height: auto; display: block; margin: 0 auto; border: 0;"
                                                                                            width="16" />
                                                                                    </td>
                                                                                    <td style="font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 14px; color: #000000; vertical-align: middle; letter-spacing: undefined; text-align: left;">{{ $company_website_url }}</td>
                                                                                </tr>
                                                                            </table>
                                                                            <table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <tr>
                                                                                    <td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 10px;">
                                                                                        @php $company_email = '' @endphp
                                                                                        @if(!empty(settings()->email))
                                                                                            @php $company_email = settings()->email; @endphp
                                                                                        @endif
                                                                                        <img align="center" alt="{{ $company_email }}" class="icon" height="16" src="{{ asset('public/admin/letters') }}/Email.png" style="height: auto; display: block; margin: 0 auto; border: 0;"
                                                                                            width="16" /></td>
                                                                                    <td style="font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 14px; color: #000000; vertical-align: middle; letter-spacing: undefined; text-align: left;">{{ $company_email }}</td>
                                                                                </tr>
                                                                            </table>
                                                                            <table cellpadding="0" cellspacing="0" class="icons-inner" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; margin-right: -4px; padding-left: 0px; padding-right: 0px;">
                                                                                <tr>
                                                                                    <td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 10px;">
                                                                                        @php $company_address = '' @endphp
                                                                                        @if(!empty(settings()->address))
                                                                                            @php $company_address = settings()->address; @endphp
                                                                                        @endif
                                                                                        <img align="center" alt="{{ $company_address }}" class="icon" height="16" src="{{ asset('public/admin/letters') }}/Address.png" style="height: auto; display: block; margin: 0 auto; border: 0;"
                                                                                            width="16" /></td>
                                                                                    <td style="font-family: 'Montserrat', 'Trebuchet MS', 'Lucida Grande', 'Lucida Sans Unicode', 'Lucida Sans', Tahoma, sans-serif; font-size: 14px; color: #000000; vertical-align: middle; letter-spacing: undefined; text-align: left;">{{ $company_address }}</td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td class="column column-2" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; text-align: left; font-weight: 400; padding-bottom: 5px; padding-top: 5px; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;"
                                                    width="50%">
                                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block block-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                                        <tr>
                                                            <td class="pad" style="padding-top:40px;width:100%;padding-right:0px;padding-left:0px;">
                                                                <div align="center" class="alignment" style="line-height:10px">
                                                                    <img src="{{ asset('public/admin/letters') }}/Bottom.png" style="height: auto; display: block; border: 0; max-width: 350px; width: 100%;" width="350" />
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
