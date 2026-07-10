<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demande_transports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->date('date_deplacement');
            $table->enum('moyen_transport', ['Taxi', 'Moto', 'Véhicule personnel', 'Location', 'Autre']);
            $table->string('motif');
            $table->decimal('cout_estime', 10, 2);
            $table->text('commentaire')->nullable();

            $table->enum('statut', ['en_attente', 'validee', 'rejetee'])->default('en_attente');

            $table->foreignId('valide_par')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->text('motif_rejet')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_transports');
    }
};
