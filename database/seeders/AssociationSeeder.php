<?php

namespace Database\Seeders;

use App\Models\Associations;
use Illuminate\Database\Seeder;

class AssociationSeeder extends Seeder
{
    public function run(): void
    {
        $associations = [
            ['name' => 'Chorale Saint Michel', 'sigle' => 'CSM'],
            ['name' => 'Mouvement des Femmes Chrétiennes', 'sigle' => 'MFC'],
            ['name' => 'Jeunesse Étudiante Chrétienne', 'sigle' => 'JEC'],
            ['name' => 'Légion de Marie', 'sigle' => 'LDM'],
            ['name' => 'Mouvement des Hommes', 'sigle' => 'MDH'],
            ['name' => 'Enfants de Choeur', 'sigle' => 'EDC'],
            ['name' => 'Mouvement des Veuves', 'sigle' => 'MDV'],
            ['name' => 'Association des Anciens', 'sigle' => 'ADA'],
            ['name' => 'Groupe de Prière Charismatique', 'sigle' => 'GPC'],
            ['name' => 'Conférence Saint Vincent de Paul', 'sigle' => 'CSVP'],
        ];

        foreach ($associations as $association) {
            Associations::create($association);
            $this->command->info("✅ Association créée : {$association['name']} ({$association['sigle']})");
        }

        $this->command->info('✅ Toutes les associations ont été créées !');
    }
}