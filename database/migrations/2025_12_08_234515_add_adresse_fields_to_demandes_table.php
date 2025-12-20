<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // On ajoute uniquement les colonnes qui manquent pour l'adresse

            if (!Schema::hasColumn('demandes', 'adresse')) {
                $table->string('adresse')->nullable()->after('telephone');
            }

            if (!Schema::hasColumn('demandes', 'code_postal')) {
                $table->string('code_postal')->nullable()->after('adresse');
            }

            if (!Schema::hasColumn('demandes', 'ville')) {
                $table->string('ville')->nullable()->after('code_postal');
            }

            // ⚠️ PAS de 'statut' ici, il existe déjà
        });
    }

    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // On supprime uniquement les colonnes qu'on a ajoutées
            if (Schema::hasColumn('demandes', 'adresse')) {
                $table->dropColumn('adresse');
            }
            if (Schema::hasColumn('demandes', 'code_postal')) {
                $table->dropColumn('code_postal');
            }
            if (Schema::hasColumn('demandes', 'ville')) {
                $table->dropColumn('ville');
            }
        });
    }
};
