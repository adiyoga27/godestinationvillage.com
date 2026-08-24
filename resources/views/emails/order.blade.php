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
                            {!! $body !!}

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:24px;border:1px solid #e8e4dc;border-radius:12px;overflow:hidden;">
                                <tr style="background-color:#f4f1ea;">
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">No. Order</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->code }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;border-top:1px solid #e8e4dc;">Paket</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;border-top:1px solid #e8e4dc;">{{ $order->package_name }}</td>
                                </tr>
                                <tr style="background-color:#f4f1ea;">
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">Desa</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->village_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">Nama</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->customer_name }}</td>
                                </tr>
                                <tr style="background-color:#f4f1ea;">
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">Jumlah Peserta</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->pax }} orang</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">Tanggal Kunjungan</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->checkin_date ? date('d M Y', strtotime($order->checkin_date)) : '-' }}</td>
                                </tr>
                                <tr style="background-color:#f4f1ea;">
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">Total Pembayaran</td>
                                    <td style="padding:12px 20px;font-size:15px;font-weight:bold;color:#d81c25;">Rp {{ number_format($order->total_payment, 0, ',', '.') }}</td>
                                </tr>
                            </table>

                            @if ($order->bank_name)
                                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;border:1px solid #e8e4dc;border-radius:12px;overflow:hidden;">
                                    <tr style="background-color:#f4f1ea;">
                                        <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">Bank Tujuan</td>
                                        <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->bank_name }} a.n. {{ $order->bank_acc_name }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;">No. Rekening</td>
                                        <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $order->bank_acc_no }}</td>
                                    </tr>
                                </table>
                            @endif

                            <p style="margin-top:24px;color:#6b6b7b;font-size:13px;">
                                Jika Anda memiliki pertanyaan, silakan hubungi kami di <a href="mailto:hello@godestinationvillage.com" style="color:#d81c25;">hello@godestinationvillage.com</a> atau WhatsApp +62 819-9767-4778.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#f4f1ea;padding:24px 40px;text-align:center;font-size:12px;color:#6b6b7b;">
                            <p style="margin:0;">GODEVI — Go Destination Village · Bali, Indonesia</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>