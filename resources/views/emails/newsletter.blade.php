<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f1ea;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f1ea;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background-color:#14141f;padding:32px 40px;text-align:center;">
                            <img src="{{ url('assets/customer/img/logo-white.png') }}" alt="GODEVI" width="140" style="border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:40px;color:#1a1a26;font-size:15px;line-height:1.7;">
                            <h1 style="margin:0 0 16px;font-size:22px;color:#14141f;">{{ $subject }}</h1>
                            {!! $content !!}
                            <p style="margin-top:24px;color:#6b6b7b;font-size:13px;">
                                Anda menerima email ini karena terdaftar sebagai subscriber newsletter GODEVI.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f4f1ea;padding:24px 40px;text-align:center;font-size:12px;color:#6b6b7b;">
                            <p style="margin:0 0 8px;">GODEVI — Go Destination Village · Bali, Indonesia</p>
                            <a href="{{ route('unsubscribe.show', $subscriber->unsubscribe_token) }}" style="color:#d81c25;text-decoration:underline;">
                                Berhenti berlangganan newsletter ini
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>