<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SellerRegistrationRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $sellerName;
    public $storeName;
    public $missingFields;
    public $errorMessages;

    /**
     * Create a new message instance.
     */
    public function __construct($sellerName, $storeName, $missingFields, $errorMessages)
    {
        $this->sellerName = $sellerName;
        $this->storeName = $storeName;
        $this->missingFields = $missingFields;
        $this->errorMessages = $errorMessages;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '❌ Registrasi Seller Ditolak - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.seller-registration-rejected',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}