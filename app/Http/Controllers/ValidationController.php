<?php

namespace App\Http\Controllers;

use App\Mail\DemandeTransportTraitee;
use App\Models\DemandeTransport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ValidationController extends Controller
{
    public function index(): View
    {
        $demandes = DemandeTransport::with(['user', 'justificatifs'])
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
}
