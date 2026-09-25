<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderEventEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $order;

    public $body;

    public function __construct($subject, $order, $body)
    {
        $this->subject = $subject;
        $this->order = $order;
        $this->body = $body;
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.order-event');
    }
}
