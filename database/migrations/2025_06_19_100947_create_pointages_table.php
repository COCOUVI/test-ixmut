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
        Schema::create('pointages', function (Blueprint $table) {
        $table->id(); // Clé primaire
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // FK vers users
        $table->date('date_pointage');
        $table->time('heure_arrivee')->nullable();
        $table->time('heure_depart')->nullable();
        $table->text('justificatif_retard')->nullable();
        $table->boolean('statut')->default(false); // vrai si validé
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointages');
    }
};
