<?php

namespace Database\Seeders;

use App\Models\Associations;
use App\Models\Offrande;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OffrandeSeeder extends Seeder
{
    public function run(): void
    {
        $associations = Associations::all();
        $currentYear = Carbon::now()->year;

        // Générer des offrandes pour chaque dimanche de l'année
        $startDate = Carbon::create($currentYear, 1, 1)->next(Carbon::SUNDAY);
        $endDate = Carbon::create($currentYear, 9, 30); // Jusqu'à aujourd'hui

        while ($startDate->lte($endDate)) {
            foreach ($associations as $association) {
                Offrande::create([
                    'somme' => rand(30000, 80000),
                    'association_id' => $association->id,
                    'offrande_day' => $startDate->format('Y-m-d'),
                ]);
            }

            $startDate->addWeek();
        }

        $this->command->info('✅ Toutes les offrandes ont été créées !');
    }
}