<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['client', 'admin', 'technicien'])->default('client');
            }

            if (!Schema::hasColumn('users', 'type_client')) {
                $table->enum('type_client', ['particulier', 'entreprise'])->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            if (Schema::hasColumn('users', 'type_client')) {
                $table->dropColumn('type_client');
            }
        });
    }
};
