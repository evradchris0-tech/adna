<?php

namespace Database\Seeders;

use App\Models\Cotisation;
use App\Models\Paroissien;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CotisationSeeder extends Seeder
{
    public function run(): void
    {
        $paroissiens = Paroissien::all();
        $currentYear = Carbon::now()->year;

        $types = ['recolte', 'autres recettes'];

        foreach ($paroissiens as $paroissien) {
            // 80% des paroissiens ont des cotisations
            if (rand(1, 100) <= 80) {
                // Nombre de cotisations par paroissien
                $nbCotisations = rand(1, 3);

                for ($i = 0; $i < $nbCotisations; $i++) {
                    Cotisation::create([
                        'paroissiens_id' => $paroissien->id,
                        'type' => $types[array_rand($types)],
                        'somme' => rand(2000, 10000),
                        'for_year' => $currentYear,
                        'dette_last_year' => 0,
                        'created_at' => Carbon::create($currentYear, rand(1, 9), rand(1, 28)),
                    ]);
                }
            }
        }

        $this->command->info('✅ Toutes les cotisations ont été créées !');
    }
}