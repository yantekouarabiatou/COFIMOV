<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Une demande peut désormais couvrir plusieurs trajets (ex : mission
     * avec plusieurs étapes). Les colonnes de trajet unique sont extraites
     * vers une table dédiée ; les demandes déjà soumises sont converties
     * en un trajet unique pour ne rien perdre.
     */
    public function up(): void
    {
        Schema::create('trajets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('demande_transport_id')
                  ->constrained('demande_transports')
                  ->cascadeOnDelete();

            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->date('date_deplacement');
            $table->enum('moyen_transport', ['Taxi', 'Moto', 'Véhicule personnel', 'Location', 'Autre']);
            $table->decimal('cout_estime', 10, 2);

            $table->timestamps();
        });

        foreach (DB::table('demande_transports')->get() as $demande) {
            DB::table('trajets')->insert([
                'demande_transport_id' => $demande->id,
                'lieu_depart' => $demande->lieu_depart,
                'lieu_arrivee' => $demande->lieu_arrivee,
                'date_deplacement' => $demande->date_deplacement,
                'moyen_transport' => $demande->moyen_transport,
                'cout_estime' => $demande->cout_estime,
                'created_at' => $demande->created_at,
                'updated_at' => $demande->updated_at,
            ]);
        }

        Schema::table('demande_transports', function (Blueprint $table) {
            $table->dropColumn(['lieu_depart', 'lieu_arrivee', 'date_deplacement', 'moyen_transport']);
        });
    }

    public function down(): void
    {
        Schema::table('demande_transports', function (Blueprint $table) {
            $table->string('lieu_depart')->nullable();
            $table->string('lieu_arrivee')->nullable();
            $table->date('date_deplacement')->nullable();
            $table->enum('moyen_transport', ['Taxi', 'Moto', 'Véhicule personnel', 'Location', 'Autre'])->nullable();
        });

        foreach (DB::table('demande_transports')->get() as $demande) {
            $premierTrajet = DB::table('trajets')
                ->where('demande_transport_id', $demande->id)
                ->orderBy('date_deplacement')
                ->first();

            if ($premierTrajet) {
                DB::table('demande_transports')->where('id', $demande->id)->update([
                    'lieu_depart' => $premierTrajet->lieu_depart,
                    'lieu_arrivee' => $premierTrajet->lieu_arrivee,
                    'date_deplacement' => $premierTrajet->date_deplacement,
                    'moyen_transport' => $premierTrajet->moyen_transport,
                ]);
            }
        }

        Schema::dropIfExists('trajets');
    }
};
