<?php

namespace App\Http\Controllers;

use App\Exports\DemandesTransportExport;
use App\Http\Controllers\Concerns\ResolvesExportPeriod;
use App\Mail\DemandeTransportSoumise;
use App\Models\DemandeTransport;
use App\Models\Justificatif;
use App\Models\Trajet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class DemandeTransportController extends Controller
{
    use ResolvesExportPeriod;

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'trajets' => ['required', 'array', 'min:1'],
            'trajets.*.lieu_depart' => ['required', 'string', 'max:255'],
            'trajets.*.lieu_arrivee' => ['required', 'string', 'max:255'],
            'trajets.*.date_deplacement' => ['required', 'date'],
            'trajets.*.moyen_transport' => ['required', 'in:Taxi,Moto,Véhicule personnel,Location,Autre'],
            'trajets.*.cout_estime' => ['required', 'numeric', 'min:0'],
            'motif' => ['required', 'string', 'max:255'],
            'commentaire' => ['nullable', 'string'],
            'justificatif' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        $demande = $request->user()->demandeTransports()->create([
            'motif' => $validated['motif'],
            'commentaire' => $validated['commentaire'] ?? null,
            'cout_estime' => collect($validated['trajets'])->sum('cout_estime'),
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

        foreach ($validated['trajets'] as $trajet) {
            $demande->trajets()->create($trajet);
        }

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

        $pdf = Pdf::loadView('pdf.demande-transport', ['demande' => $demande->load('user.poste', 'trajets')]);

        return $pdf->download("demande-transport-{$demande->id}.pdf");
    }

    public function exportPdf(Request $request): Response
    {
        [$from, $to] = $this->resolvePeriod($request);

        $trajets = Trajet::with('demandeTransport.user')
            ->whereHas('demandeTransport', fn ($query) => $query->where('user_id', $request->user()->id))
            ->whereBetween('date_deplacement', [$from, $to])
            ->orderBy('date_deplacement')
            ->get();

        $pdf = Pdf::loadView('pdf.rapport-demandes', [
            'trajets' => $trajets,
            'from' => $from,
            'to' => $to,
            'showCollaborateur' => false,
            'titre' => 'Mes demandes de frais de transport',
        ]);

        return $pdf->download("mes-demandes-{$from->format('Y-m-d')}-au-{$to->format('Y-m-d')}.pdf");
    }

    public function exportExcel(Request $request)
    {
        [$from, $to] = $this->resolvePeriod($request);

        return Excel::download(
            new DemandesTransportExport($from, $to, $request->user()->id),
            "mes-demandes-{$from->format('Y-m-d')}-au-{$to->format('Y-m-d')}.xlsx"
        );
    }
}
