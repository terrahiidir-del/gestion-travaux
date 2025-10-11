<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ⚠️ Exemple : on peut garder ce user de test, ou commenter si inutile
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // ✅ Ajout de l’appel au DisponibiliteSeeder
        $this->call([
            DisponibiliteSeeder::class,
        ]);
    }
}
