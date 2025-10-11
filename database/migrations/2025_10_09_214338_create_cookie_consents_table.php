<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cookie_consents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $t->string('visitor_id', 100)->nullable();
            $t->json('choices');
            $t->string('policy_version')->default('1.0');
            $t->ipAddress('ip')->nullable();
            $t->timestamp('consented_at');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('cookie_consents');
    }
};
