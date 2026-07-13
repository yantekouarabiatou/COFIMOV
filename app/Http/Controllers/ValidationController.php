<?php

namespace App\Http\Controllers;

use App\Exports\DemandesTransportExport;
use App\Http\Controllers\Concerns\ResolvesExportPeriod;
use App\Mail\DemandeTransportTraitee;
use App\Models\DemandeTransport;
use App\Models\Trajet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ValidationController extends Controller
{
    use ResolvesExportPeriod;

    public function index(): View
    {
        $demandes = DemandeTransport::with(['user', 'justificatifs', 'trajets'])
            ->where('statut', DemandeTransport::STATUT_EN_ATTENTE)
            ->latest()
            ->get();

        return view('validation.index', [
            'demandes' => $demandes,
        ]);
    }

    public function valider(Request $request, DemandeTransport $demande): RedirectResponse
    {
        $demande->update([
            'statut' => DemandeTransport::STATUT_VALIDEE,
            'valide_par' => $request->user()->id,
            'date_validation' => now(),
        ]);

        $demande->historiques()->create([
            'user_id' => $request->user()->id,
            'statut' => DemandeTransport::STATUT_VALIDEE,
            'commentaire' => 'Demande validée.',
        ]);

        Mail::to($demande->user->email)->send(new DemandeTransportTraitee($demande));

        return back()->with('status', 'demande-validee');
    }

    public function rejeter(Request $request, DemandeTransport $demande): RedirectResponse
    {
        $validated = $request->validate([
            'motif_rejet' => ['required', 'string', 'max:1000'],
        ]);

        $demande->update([
            'statut' => DemandeTransport::STATUT_REJETEE,
            'valide_par' => $request->user()->id,
            'date_validation' => now(),
            'motif_rejet' => $validated['motif_rejet'],
        ]);

        $demande->historiques()->create([
            'user_id' => $request->user()->id,
            'statut' => DemandeTransport::STATUT_REJETEE,
            'commentaire' => $validated['motif_rejet'],
        ]);

        Mail::to($demande->user->email)->send(new DemandeTransportTraitee($demande));

        return back()->with('status', 'demande-rejetee');
    }

    public function exportPdf(Request $request): Response
    {
        [$from, $to] = $this->resolvePeriod($request);

        $trajets = Trajet::with('demandeTransport.user')
            ->whereBetween('date_deplacement', [$from, $to])
            ->orderBy('date_deplacement')
            ->get();

        $pdf = Pdf::loadView('pdf.rapport-demandes', [
            'trajets' => $trajets,
            'from' => $from,
            'to' => $to,
            'showCollaborateur' => true,
            'titre' => 'Rapport des demandes de frais de transport',
        ]);

        return $pdf->download("rapport-demandes-{$from->format('Y-m-d')}-au-{$to->format('Y-m-d')}.pdf");
    }

    public function exportExcel(Request $request)
    {
        [$from, $to] = $this->resolvePeriod($request);

        return Excel::download(
            new DemandesTransportExport($from, $to, null),
            "rapport-demandes-{$from->format('Y-m-d')}-au-{$to->format('Y-m-d')}.xlsx"
        );
    }
}
