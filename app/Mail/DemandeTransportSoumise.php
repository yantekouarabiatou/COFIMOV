<?php

namespace App\Mail;

use App\Models\DemandeTransport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeTransportSoumise extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DemandeTransport $demande)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de frais de transport — '.$this->demande->user->full_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.demande-transport-soumise',
            with: ['demande' => $this->demande],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $demande = $this->demande->loadMissing('user.poste', 'trajets');

        return [
            Attachment::fromData(
                fn () => Pdf::loadView('pdf.demande-transport', ['demande' => $demande])->output(),
                "demande-transport-{$demande->id}.pdf",
            )->withMime('application/pdf'),
        ];
    }
}
