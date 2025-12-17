<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Deleted</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* General Reset */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            font-family: Arial, sans-serif;
            background-color: #f4f4f7;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #e0e0e0;
        }

        /* Header */
        .header {
            background-color: #dc3545;
            color: #ffffff;
            text-align: center;
            padding: 25px 15px;
            font-size: 22px;
            font-weight: bold;
        }

        /* Content */
        .content {
            padding: 25px 20px;
            color: #333333;
            line-height: 1.6;
        }

        .content p {
            margin-bottom: 15px;
        }

        .reason-box {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
            padding: 15px;
            border-radius: 5px;
            color: #721c24;
            font-style: italic;
            margin: 15px 0;
        }

        .btn {
            display: inline-block;
            background-color: #dc3545;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .btn:hover {
            background-color: #c82333;
        }

        /* Footer */
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #888888;
        }

        /* Responsive */
        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }

            .content, .header {
                padding: 20px !important;
            }

            .btn {
                width: 100% !important;
                box-sizing: border-box;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="email-container" cellpadding="0" cellspacing="0">
                    <!-- Header -->
                    <tr>
                        <td class="header">
                            Account Deleted
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content">
                            <p>Hello <strong>{{ $user->first_name }}</strong>,</p>
                            <p>We wanted to let you know that your account has been deleted from our system.</p>

                            <div class="reason-box">
                                <strong>Reason:</strong> {{ $reason }}
                            </div>

                            <p>If you believe this is a mistake, please contact our support team immediately.</p>

                            <p>Regards,<br>Admin</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            &copy; {{ date('Y') }} Nwlogics. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
