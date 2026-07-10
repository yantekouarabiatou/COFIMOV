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
        Schema::create('justificatifs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('demande_transport_id')
                  ->constrained('demande_transports')
                  ->cascadeOnDelete();

            $table->string('nom_original');
            $table->string('chemin');
            $table->string('type_mime')->nullable();
            $table->unsignedInteger('taille')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justificatifs');
    }
};
