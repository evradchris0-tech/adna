<?php

namespace Database\Seeders;

use App\Models\Engagements;
use App\Models\Versements;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VersementSeeder extends Seeder
{
    public function run(): void
    {
        $engagements = Engagements::with('paroissien')->get();
        $currentYear = Carbon::now()->year;

        foreach ($engagements as $engagement) {
            // Nombre aléatoire de versements pour chaque engagement
            $nbVersements = rand(1, 6);
            
            $dimeRestant = $engagement->dime;
            $cotisationRestant = $engagement->cotisation;
            
            $totalDimeVerse = 0;
            $totalCotisationVerse = 0;

            for ($i = 0; $i < $nbVersements; $i++) {
                // Alterner entre dîme et cotisation
                if ($i % 2 === 0 && $dimeRestant > 0) {
                    // Versement dîme
                    $montant = min(rand(10000, 30000), $dimeRestant);
                    
                    Versements::create([
                        'paroissiens_id' => $engagement->paroissiens_id,
                        'engagement_id' => $engagement->id,
                        'type' => 'dime',
                        'somme' => $montant,
                        'created_at' => Carbon::create($currentYear, rand(1, 9), rand(1, 28)),
                    ]);

                    $dimeRestant -= $montant;
                    $totalDimeVerse += $montant;
                } elseif ($cotisationRestant > 0) {
                    // Versement cotisation
                    $montant = min(rand(5000, 15000), $cotisationRestant);
                    
                    Versements::create([
                        'paroissiens_id' => $engagement->paroissiens_id,
                        'engagement_id' => $engagement->id,
                        'type' => 'Offrande de construction',
                        'somme' => $montant,
                        'created_at' => Carbon::create($currentYear, rand(1, 9), rand(1, 28)),
                    ]);

                    $cotisationRestant -= $montant;
                    $totalCotisationVerse += $montant;
                }
            }

            // Mettre à jour les montants disponibles
            $engagement->update([
                'available_dime' => $totalDimeVerse,
                'available_cotisation' => $totalCotisationVerse,
            ]);
        }

        $this->command->info('✅ Tous les versements ont été créés !');
    }
}