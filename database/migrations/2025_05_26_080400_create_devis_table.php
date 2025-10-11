<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void 
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 10, 2);
            $table->text('description'); // changé de "details" à "description"
            $table->unsignedBigInteger('demande_id'); // type compatible
            $table->foreign('demande_id')
                  ->references('id')->on('demandes')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
