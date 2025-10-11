<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->string('type_travaux')->nullable();
            $table->text('description')->nullable()->change();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('ville')->nullable();
        });
    }

    public function down()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'type_travaux', 'nom', 'prenom', 'email', 'telephone', 'adresse', 'code_postal', 'ville'
            ]);
        });
    }
};
