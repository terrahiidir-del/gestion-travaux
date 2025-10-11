<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Disponibilite;
use Carbon\Carbon;

class DisponibiliteSeeder extends Seeder
{
    public function run(): void
    {
        $techniciens = User::where('role', 'technicien')->get();

        for ($day = 0; $day < 30; $day++) {
            $date = Carbon::now()->addDays($day);
            if ($date->isSunday()) continue;

            foreach ($techniciens as $tech) {
                for ($hour = 8; $hour < 16; $hour++) {
                    Disponibilite::updateOrCreate(
                        [
                            'user_id' => $tech->id,
                            'date'    => $date->format('Y-m-d'),
                            'heure'   => sprintf('%02d:00:00', $hour),
                        ],
                        [] // rien à mettre côté update
                    );
                }
            }
        }
    }
}
