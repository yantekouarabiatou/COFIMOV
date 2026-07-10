<?php

namespace App\Http\Controllers;

use App\Mail\DemandeTransportSoumise;
use App\Models\DemandeTransport;
use App\Models\Justificatif;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class DemandeTransportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lieu_depart' => ['required', 'string', 'max:255'],
            'lieu_arrivee' => ['required', 'string', 'max:255'],
            'date_deplacement' => ['required', 'date'],
            'moyen_transport' => ['required', 'in:Taxi,Moto,Véhicule personnel,Location,Autre'],
            'motif' => ['required', 'string', 'max:255'],
            'cout_estime' => ['required', 'numeric', 'min:0'],
            'commentaire' => ['nullable', 'string'],
            'justificatif' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $demande = $request->user()->demandeTransports()->create([
            ...$validated,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

        if ($request->hasFile('justificatif')) {
            $file = $request->file('justificatif');
            $chemin = $file->store('justificatifs', 'public');

            Justificatif::create([
                'demande_transport_id' => $demande->id,
                'nom_original' => $file->getClientOriginalName(),
                'chemin' => $chemin,
                'type_mime' => $file->getClientMimeType(),
                'taille' => $file->getSize(),
            ]);
        }

        $demande->historiques()->create([
            'user_id' => $request->user()->id,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
            'commentaire' => 'Demande soumise.',
        ]);

        $destinataires = config('cofima.email_dg');

        if (! empty($destinataires)) {
            Mail::to($destinataires)
                ->cc(config('cofima.email_secretariat'))
                ->send(new DemandeTransportSoumise($demande));
        }

        return redirect()->route('dashboard')->with('status', 'demande-soumise');
    }

    public function pdf(Request $request, DemandeTransport $demande): Response
    {
        abort_unless(
            $request->user()->id === $demande->user_id || $request->user()->hasAnyRole(['directeur-general', 'admin']),
            403
        );

        $pdf = Pdf::loadView('pdf.demande-transport', ['demande' => $demande->load('user.poste')]);

        return $pdf->download("demande-transport-{$demande->id}.pdf");
    }
}
