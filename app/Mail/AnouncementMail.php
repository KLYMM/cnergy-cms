<?php

namespace App\Mail;

use App\Models\Anouncement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $anouncement;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($anouncement)
    {
     $this->anouncement = $anouncement;   
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('ersgandul@gmail.com', 'No Reply | KLY MAILER'),
            subject: 'New Anouncement Published',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'email.anouncementMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}