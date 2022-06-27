<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
<style>
    @media only screen and (max-width: 600px) {
        .inner-body {
            width: 100% !important;
        }

        .footer {
            width: 100% !important;
        }
    }

    @media only screen and (max-width: 500px) {
        .button {
            width: 100% !important;
        }
    }

    .wrapper {
        background: linear-gradient(to top, transparent, rgba(193, 255, 130, 1)), linear-gradient(to bottom, transparent, rgba(254, 255, 253, 0)), url("<?= url('images/email-images/grocery-bg.png') ?>");
    }
    .header {
        background: linear-gradient(to top, transparent, rgba(194, 255, 131, 0)), linear-gradient(to bottom, transparent, rgba(198, 255, 140, 0)), url("<?= url('images/email-images/header-bg.png') ?>");
        background-size: contain;
    }

    #footer {
        margin: 10px 0px 0px 0px;
        text-align: center;
        color: green;
        text-decoration: none;
    }

    #footer span {
        font-size: 14px;
        vertical-align: middle;
        color: #9a6e3a;
    }

    #footer img {
        vertical-align: middle;
    }

    #footer .left {
        font-size: 14px;
        margin-right: 50px;
        text-decoration: none;
        color: #9a6e3a;
    }

    #footer_next {
        margin-top: 15px;
        color: #9a6e3a;
    }

</style>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0">
            {{ $header ?? '' }}

            <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    {{ Illuminate\Mail\Markdown::parse($slot) }}

                                    {{ $subcopy ?? '' }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{ $footer ?? '' }}
            </table>
        </td>
    </tr>
</table>
</body>
</html>
