<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Justificatif extends Model
{
    protected $fillable = [
        'demande_transport_id',
        'nom_original',
        'chemin',
        'type_mime',
        'taille',
    ];

    public function demandeTransport(): BelongsTo
    {
        return $this->belongsTo(DemandeTransport::class);
    }
}
