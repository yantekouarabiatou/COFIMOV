<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Le collaborateur peut désormais annuler une demande encore en
     * attente : ajout du statut 'annulee' à l'énumération existante.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE demande_transports MODIFY COLUMN statut ENUM('en_attente', 'validee', 'rejetee', 'annulee') NOT NULL DEFAULT 'en_attente'");
        DB::statement("ALTER TABLE demande_transport_historiques MODIFY COLUMN statut ENUM('en_attente', 'validee', 'rejetee', 'annulee') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE demande_transport_historiques MODIFY COLUMN statut ENUM('en_attente', 'validee', 'rejetee') NOT NULL");
        DB::statement("ALTER TABLE demande_transports MODIFY COLUMN statut ENUM('en_attente', 'validee', 'rejetee') NOT NULL DEFAULT 'en_attente'");
    }
};
