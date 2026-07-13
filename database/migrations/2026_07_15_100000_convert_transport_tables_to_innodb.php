<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Le serveur MySQL partagé utilise MyISAM par défaut : les tables
     * demande_transports/trajets/justificatifs/historiques ont donc été
     * créées sans réel support transactionnel, et les clauses de clé
     * étrangère (cascadeOnDelete) ont été silencieusement ignorées par
     * MyISAM. Ces 4 tables appartiennent entièrement à cette application
     * (contrairement à users/postes/roles, partagées avec 2 autres apps)
     * : on peut donc les passer en InnoDB sans risque pour le reste de
     * la base, et ajouter de vraies contraintes de clé étrangère entre
     * elles pour un cascade delete fiable.
     */
    public function up(): void
    {
        foreach (['demande_transports', 'trajets', 'justificatifs', 'demande_transport_historiques'] as $table) {
            $engine = DB::selectOne(
                "SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?",
                [$table]
            )->ENGINE ?? null;

            if ($engine !== 'InnoDB') {
                DB::statement("ALTER TABLE {$table} ENGINE=InnoDB");
            }
        }

        $this->addForeignKeyIfMissing('trajets', 'demande_transport_id', 'demande_transports');
        $this->addForeignKeyIfMissing('justificatifs', 'demande_transport_id', 'demande_transports');
        $this->addForeignKeyIfMissing('demande_transport_historiques', 'demande_transport_id', 'demande_transports');
    }

    private function addForeignKeyIfMissing(string $table, string $column, string $references): void
    {
        $constraintName = "{$table}_{$column}_foreign";

        $exists = DB::selectOne(
            "SELECT 1 FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?",
            [$table, $constraintName]
        );

        if (! $exists) {
            Schema::table($table, function ($tableBlueprint) use ($column, $references) {
                $tableBlueprint->foreign($column)
                    ->references('id')->on($references)
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('trajets', function ($table) {
            $table->dropForeign(['demande_transport_id']);
        });

        Schema::table('justificatifs', function ($table) {
            $table->dropForeign(['demande_transport_id']);
        });

        Schema::table('demande_transport_historiques', function ($table) {
            $table->dropForeign(['demande_transport_id']);
        });

        foreach (['demande_transports', 'trajets', 'justificatifs', 'demande_transport_historiques'] as $table) {
            DB::statement("ALTER TABLE {$table} ENGINE=MyISAM");
        }
    }
};
