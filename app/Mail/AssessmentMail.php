<?php

namespace App\Mail;

use App\Models\AssessmentOrder;
use App\Models\AssessmentResult;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email asesmen: 'invoice' (menunggu bayar), 'paid' (pembayaran berhasil), 'report' (hasil & strategi).
 */
class AssessmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public const SUBJECTS = [
        'invoice' => 'Invoice Asesmen GODEVI',
        'paid' => 'Pembayaran Asesmen Berhasil',
        'report' => 'Hasil Asesmen & Strategi Anda',
    ];

    public function __construct(
        public string $type,
        public AssessmentResult $result,
        public ?AssessmentOrder $order = null,
    ) {}

    public function envelope(): Envelope
    {
        $suffix = $this->order ? ' — '.$this->order->code : ' — '.($this->result->track->name ?? 'GODEVI');

        return new Envelope(subject: self::SUBJECTS[$this->type].$suffix);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.assessment', with: [
            'subject' => self::SUBJECTS[$this->type],
            'resultUrl' => route('assessment.result', $this->result->uuid),
            'paymentUrl' => $this->order ? route('assessment.payment', $this->order->code) : null,
        ]);
    }
}
