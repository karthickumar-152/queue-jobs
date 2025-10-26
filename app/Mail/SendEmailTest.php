<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmailTest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public function __construct() {}

    /**
     * Get the message envelope.
     */

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mail from demo app',
        );
    }

    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */

    public function attachments(): array
    {
        return [];
    }
}
