<?php

namespace App\Http\Controllers;

use App\Models\DemandeTransport;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = [
            'collaborateurs' => User::where('is_active', true)->count(),
            'demandes_traitees' => DemandeTransport::whereIn('statut', [
                DemandeTransport::STATUT_VALIDEE,
                DemandeTransport::STATUT_REJETEE,
            ])->count(),
            'montant_rembourse' => DemandeTransport::where('statut', DemandeTransport::STATUT_VALIDEE)->sum('cout_estime'),
        ];

        return view('welcome', ['stats' => $stats]);
    }
}
