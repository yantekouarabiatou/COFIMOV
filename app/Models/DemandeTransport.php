<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemandeTransport extends Model
{
    public const STATUT_EN_ATTENTE = 'en_attente';
    public const STATUT_VALIDEE = 'validee';
    public const STATUT_REJETEE = 'rejetee';

    protected $fillable = [
        'user_id',
        'lieu_depart',
        'lieu_arrivee',
        'date_deplacement',
        'moyen_transport',
        'motif',
        'cout_estime',
        'commentaire',
        'statut',
        'valide_par',
        'date_validation',
        'motif_rejet',
    ];

    protected function casts(): array
    {
        return [
            'date_deplacement' => 'date',
            'date_validation' => 'datetime',
            'cout_estime' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function justificatifs(): HasMany
    {
        return $this->hasMany(Justificatif::class);
    }

    public function historiques(): HasMany
    {
        return $this->hasMany(DemandeTransportHistorique::class)->latest();
    }
}
