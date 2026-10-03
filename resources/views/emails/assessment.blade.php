@php
    $track = $result->track;
    $rep = $result->ai_report ?? [];
    $rupiah = fn ($n) => 'Rp '.number_format((int) $n, 0, ',', '.');
    // Item laporan AI bisa berupa string atau objek (mis. {produk, alasan}).
    $text = fn ($v) => is_array($v) ? implode(' — ', array_map(fn ($x) => is_array($x) ? implode(', ', $x) : $x, array_filter($v))) : (string) $v;
    $row = 'padding:12px 20px;font-size:13px;color:#1a1a26;border-top:1px solid #e8e4dc;';
    $label = 'padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;border-top:1px solid #e8e4dc;width:40%;';
    $button = 'display:inline-block;background-color:#d81c25;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;padding:14px 28px;border-radius:12px;';
@endphp
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
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background-color:#ffffff;padding:28px 40px;text-align:center;border-bottom:4px solid #d81c25;">
                            <img src="{{ url('assets/godevi-black.png') }}" alt="GODEVI" width="150" style="border:0;height:auto;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 40px;color:#1a1a26;font-size:15px;line-height:1.7;">
                            <h1 style="margin:0 0 12px;font-size:22px;color:#14141f;">{{ $subject }}</h1>
                            <p style="margin:0 0 16px;">Halo {{ $result->name }},</p>

                            @if ($type === 'invoice')
                                <p style="margin:0 0 16px;">Terima kasih telah mengisi asesmen <strong>{{ $track->name }}</strong>. Skor dan laporan strategi Anda akan terbuka setelah pembayaran berikut diselesaikan.</p>
                            @elseif ($type === 'paid')
                                <p style="margin:0 0 16px;">Pembayaran asesmen <strong>{{ $track->name }}</strong> telah kami terima. Laporan analisa & strategi sedang disusun dan akan kami kirim ke email ini begitu siap.</p>
                            @else
                                <p style="margin:0 0 16px;">Hasil asesmen <strong>{{ $track->name }}</strong> untuk <strong>{{ $result->organization }}</strong> sudah siap. Berikut ringkasannya — laporan lengkap bisa dibuka lewat tombol di bawah.</p>
                            @endif

                            {{-- Ringkasan transaksi / asesmen --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:8px;border:1px solid #e8e4dc;border-radius:12px;overflow:hidden;border-collapse:separate;">
                                <tr style="background-color:#f4f1ea;">
                                    <td style="padding:12px 20px;font-weight:bold;font-size:13px;color:#14141f;width:40%;">Jalur Asesmen</td>
                                    <td style="padding:12px 20px;font-size:13px;color:#1a1a26;">{{ $track->name }}</td>
                                </tr>
                                <tr><td style="{{ $label }}">Nama Usaha / Desa / Kawasan</td><td style="{{ $row }}">{{ $result->organization }}</td></tr>
                                <tr><td style="{{ $label }}">Lokasi</td><td style="{{ $row }}">{{ collect([$result->regency, $result->province])->filter()->implode(', ') }}</td></tr>
                                @if ($order)
                                    <tr><td style="{{ $label }}">No. Invoice</td><td style="{{ $row }}"><strong>{{ $order->code }}</strong></td></tr>
                                    <tr><td style="{{ $label }}">Total</td><td style="{{ $row }}"><strong>{{ $rupiah($order->amount) }}</strong></td></tr>
                                    <tr>
                                        <td style="{{ $label }}">Status</td>
                                        <td style="{{ $row }}">
                                            @if ($order->status === 'paid')
                                                <span style="color:#15803d;font-weight:bold;">LUNAS</span>{{ $order->paid_at ? ' · '.$order->paid_at->format('d M Y H:i') : '' }}{{ $order->payment_type ? ' · '.strtoupper(str_replace('_', ' ', $order->payment_type)) : '' }}
                                            @else
                                                <span style="color:#b45309;font-weight:bold;">MENUNGGU PEMBAYARAN</span> · berlaku 24 jam
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if ($type === 'report')
                                    <tr><td style="{{ $label }}">Skor Total</td><td style="{{ $row }}"><strong style="font-size:18px;color:#d81c25;">{{ number_format($result->total_score, 0) }}/100</strong> — {{ $result->band }}</td></tr>
                                @endif
                            </table>

                            @if ($type === 'report' && $rep)
                                @if (! empty($rep['ringkasan']))
                                    <p style="margin:24px 0 0;padding:16px 20px;border-left:4px solid #d81c25;background-color:#fef2f2;border-radius:0 12px 12px 0;font-style:italic;">{{ $text($rep['ringkasan']) }}</p>
                                @endif
                                @foreach (['kekuatan' => ['Kekuatan utama', '#15803d'], 'tantangan' => ['Tantangan utama', '#b4141d']] as $key => [$title, $color])
                                    @if (! empty($rep[$key]))
                                        <h2 style="margin:24px 0 8px;font-size:16px;color:{{ $color }};">{{ $title }}</h2>
                                        <ul style="margin:0;padding-left:20px;font-size:14px;">
                                            @foreach ((array) $rep[$key] as $item)<li style="margin-bottom:6px;">{{ $text($item) }}</li>@endforeach
                                        </ul>
                                    @endif
                                @endforeach
                                @if (! empty($rep['langkah_prioritas']))
                                    <h2 style="margin:24px 0 8px;font-size:16px;color:#14141f;">Langkah prioritas</h2>
                                    <ol style="margin:0;padding-left:20px;font-size:14px;">
                                        @foreach ((array) $rep['langkah_prioritas'] as $item)<li style="margin-bottom:6px;">{{ $text($item) }}</li>@endforeach
                                    </ol>
                                @endif
                            @endif

                            <p style="margin:28px 0 8px;text-align:center;">
                                @if ($type === 'invoice' && $paymentUrl)
                                    <a href="{{ $paymentUrl }}" style="{{ $button }}">Bayar Sekarang</a>
                                @else
                                    <a href="{{ $resultUrl }}" style="{{ $button }}">{{ $type === 'report' ? 'Buka Laporan Lengkap' : 'Lihat Status Asesmen' }}</a>
                                @endif
                            </p>
                            <p style="margin:16px 0 0;font-size:12px;color:#8a8797;text-align:center;">Simpan email ini. Anda juga bisa membuka hasil kapan saja lewat menu <em>Cek Status</em> di godestinationvillage.com dengan nomor WhatsApp yang didaftarkan.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color:#14141f;padding:20px 40px;text-align:center;font-size:12px;color:#a6a3b3;">
                            PT Banua Wisata Lestari · GODEVI — Go Destination Village<br>
                            Email ini dikirim otomatis, mohon tidak membalas.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
