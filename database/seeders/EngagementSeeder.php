<?php

namespace Database\Seeders;

use App\Models\Engagements;
use App\Models\Paroissien;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EngagementSeeder extends Seeder
{
    public function run(): void
    {
        $paroissiens = Paroissien::with('association')->get();
        $year = Carbon::now()->year;

        $periodeStart = Carbon::create($year, 1, 1);
        $periodeEnd = Carbon::create($year, 12, 31);

        foreach ($paroissiens as $paroissien) {
            // Montants basés sur la catégorie
            $montants = $this->getMontantsByCategorie($paroissien->categorie);

            Engagements::create([
                'paroissiens_id' => $paroissien->id,
                'associations_id' => $paroissien->association_id,
                'periode_start' => $periodeStart->format('Y-m-d'),
                'periode_end' => $periodeEnd->format('Y-m-d'),
                'dime' => $montants['dime'],
                'offrande' => 0,
                'cotisation' => $montants['cotisation'],
                'dette_dime' => 0,
                'available_dime' => 0,
                'dette_cotisation' => 0,
                'available_cotisation' => 0,
                'available_dette_dime' => 0,
                'available_dette_cotisation' => 0,
            ]);
        }

        $this->command->info('✅ Tous les engagements ont été créés !');
    }

    private function getMontantsByCategorie(string $categorie): array
    {
        return match($categorie) {
            'ancien' => [
                'dime' => rand(150000, 200000),
                'cotisation' => rand(60000, 80000),
            ],
            'diacre' => [
                'dime' => rand(120000, 150000),
                'cotisation' => rand(50000, 60000),
            ],
            default => [
                'dime' => rand(60000, 120000),
                'cotisation' => rand(25000, 50000),
            ],
        };
    }
}