<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {width: 100% !important;}
            .footer {width: 100% !important;}
        }

        @media only screen and (max-width: 500px) {
            .button {width: 100% !important;}
        }

		.header {color: #333; font-size: 19px; font-weight: bold; text-shadow: 0 1px 0 white;}
		.content-cell {font-size: 16px; line-height: 1.5;}
        .button {font-size: 16px; margin-bottom: 25px;}
		
		/* #tr-logo td {border-bottom: 1px solid #bebebe;} */
		#logo {margin-bottom: 30px!important;}
		#tr-content td #receiver, #tr-content td p {font-size: inherit!important; color: #333!important;}
		#tr-content td p.ql-align-center {text-align: center!important;}
		#tr-content td p.ql-align-right {text-align: right!important;}
		#tr-content td p.ql-align-justify {text-align: justify!important;}
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
					
                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="750" cellpadding="0" cellspacing="0" role="presentation">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
										<table class="subcopy" width="100%" cellpadding="0" cellspacing="0" role="presentation">
											<tr id="tr-logo">
												<td align="center">
													<img id="logo" src="{{ asset('assets/images/logo/'.get_logo()) }}" width="250">
												</td>
											</tr>
											<tr id="tr-content">
												<td>
													<span id="receiver">Hai <strong>{{ is_object($receiver) ? $receiver->nama_user : $receiver }},</strong></span>
													<br><br>
													{!! html_entity_decode($message) !!}
												</td>
											</tr>
										</table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

					<tr>
						<td>
							<table class="footer" align="center" width="750" cellpadding="0" cellspacing="0" role="presentation">
								<tr>
									<td class="content-cell" align="center">
										© {{ date('Y') }} {{ get_website_name() }}. All rights reserved.
									</td>
								</tr>
							</table>
						</td>
					</tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
