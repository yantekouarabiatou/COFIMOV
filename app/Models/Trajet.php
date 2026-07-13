<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trajet extends Model
{
    protected $fillable = [
        'demande_transport_id',
        'lieu_depart',
        'lieu_arrivee',
        'date_deplacement',
        'moyen_transport',
        'cout_estime',
    ];

    protected function casts(): array
    {
        return [
            'date_deplacement' => 'date',
            'cout_estime' => 'decimal:2',
        ];
    }

    public function demandeTransport(): BelongsTo
    {
        return $this->belongsTo(DemandeTransport::class);
    }
}
