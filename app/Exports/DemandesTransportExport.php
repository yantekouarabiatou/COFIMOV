<?php

namespace App\Exports;

use App\Models\DemandeTransport;
use App\Models\Trajet;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DemandesTransportExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function __construct(
        private Carbon $from,
        private Carbon $to,
        private ?int $userId = null,
    ) {
    }

    public function collection()
    {
        return Trajet::with('demandeTransport.user')
            ->when(
                $this->userId,
                fn ($query) => $query->whereHas(
                    'demandeTransport',
                    fn ($q) => $q->where('user_id', $this->userId)
                )
            )
            ->whereBetween('date_deplacement', [$this->from, $this->to])
            ->orderBy('date_deplacement')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Collaborateur',
            'Date du déplacement',
            'Lieu de départ',
            'Lieu d\'arrivée',
            'Moyen de transport',
            'Coût estimé (FCFA)',
            'Statut de la demande',
            'Motif de la demande',
        ];
    }

    public function map($trajet): array
    {
        $demande = $trajet->demandeTransport;

        return [
            $demande->user->full_name,
            $trajet->date_deplacement->format('d/m/Y'),
            $trajet->lieu_depart,
            $trajet->lieu_arrivee,
            $trajet->moyen_transport,
            (float) $trajet->cout_estime,
            match ($demande->statut) {
                DemandeTransport::STATUT_EN_ATTENTE => 'En attente',
                DemandeTransport::STATUT_VALIDEE => 'Validée',
                DemandeTransport::STATUT_REJETEE => 'Rejetée',
            },
            $demande->motif,
        ];
    }
}
