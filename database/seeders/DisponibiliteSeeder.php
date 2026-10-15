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
            $date = Carbon::now()->addDays($day);//Calculer la date
            if ($date->isSunday()) continue;

            foreach ($techniciens as $tech) {
                for ($hour = 8; $hour < 16; $hour++) {
                    Disponibilite::updateOrCreate(  //si la disponibilité existe déjà, on la met à joursinon, on la crée
                        [
                             'user_id' => $tech->id,
                            'date'    => $date->format('Y-m-d'),
                            'heure'   => sprintf('%02d:00:00', $hour),
                        ],
                        [] 
                    );
                }
            }
        }
    }
}
