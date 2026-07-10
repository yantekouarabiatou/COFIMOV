<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemandeTransportHistorique extends Model
{
    protected $fillable = [
        'demande_transport_id',
        'user_id',
        'statut',
        'commentaire',
    ];

    public function demandeTransport(): BelongsTo
    {
        return $this->belongsTo(DemandeTransport::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
