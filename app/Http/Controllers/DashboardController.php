<?php

namespace App\Http\Controllers;

use App\Models\DemandeTransport;
use App\Models\DemandeTransportHistorique;
use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $demandes = $user->demandeTransports()->latest()->with(['justificatifs', 'trajets'])->get();

        $stats = [
            'en_attente' => $demandes->where('statut', DemandeTransport::STATUT_EN_ATTENTE)->count(),
            'validee' => $demandes->where('statut', DemandeTransport::STATUT_VALIDEE)->count(),
            'rejetee' => $demandes->where('statut', DemandeTransport::STATUT_REJETEE)->count(),
            'total_mois' => Trajet::whereHas('demandeTransport', fn ($query) => $query->where('user_id', $user->id))
                ->whereBetween('date_deplacement', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('cout_estime'),
        ];

        $historiques = DemandeTransportHistorique::whereIn('statut', [
                DemandeTransport::STATUT_VALIDEE,
                DemandeTransport::STATUT_REJETEE,
            ])
            ->whereHas('demandeTransport', fn ($query) => $query->where('user_id', $user->id))
            ->with(['demandeTransport', 'user'])
            ->latest()
            ->get();

        return view('dashboard', [
            'demandes' => $demandes,
            'stats' => $stats,
            'historiques' => $historiques,
        ]);
    }
}
