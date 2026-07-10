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

class DemandeTransportTraitee extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public DemandeTransport $demande)
    {
    }

    public function envelope(): Envelope
    {
        $statut = $this->demande->statut === DemandeTransport::STATUT_VALIDEE ? 'validée' : 'rejetée';

        return new Envelope(
            subject: "Votre demande de transport a été {$statut}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.demande-transport-traitee',
            with: ['demande' => $this->demande],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $demande = $this->demande->loadMissing('user.poste');

        return [
            Attachment::fromData(
                fn () => Pdf::loadView('pdf.demande-transport', ['demande' => $demande])->output(),
                "demande-transport-{$demande->id}.pdf",
            )->withMime('application/pdf'),
        ];
    }
}
