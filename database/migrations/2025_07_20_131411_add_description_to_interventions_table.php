<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('interventions', function (Blueprint $table) {
        $table->text('description_technicien')->nullable();
    });
}

public function down()
{
    Schema::table('interventions', function (Blueprint $table) {
        $table->dropColumn('description_technicien');
    });
}

};
