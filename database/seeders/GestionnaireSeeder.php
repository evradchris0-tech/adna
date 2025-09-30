<?php

namespace Database\Seeders;

use App\Models\Associations;
use App\Models\Gestionnaires;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class GestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $associations = Associations::all();
        $gestionnaireRole = Role::where('name', 'gestionnaire')->first();
        $responsableRole = Role::where('name', 'responsable_association')->first();

        if (!$gestionnaireRole || !$responsableRole) {
            $this->command->error('❌ Rôles gestionnaire ou responsable_association introuvables.');
            return;
        }

        // Gestionnaires avec rôle gestionnaire
        $gestionnaires = User::role('gestionnaire')->get();
        
        foreach ($gestionnaires as $index => $user) {
            if ($index < $associations->count()) {
                Gestionnaires::create([
                    'association_id' => $associations[$index]->id,
                    'role_id' => $gestionnaireRole->id,
                    'user_id' => $user->id,
                    'name' => $user->firstname . ' ' . $user->lastname,
                    'address' => 'Quartier Bastos, Yaoundé',
                    'phone' => $user->phone,
                    'statut' => 'Actif',
                ]);

                $this->command->info("✅ Gestionnaire créé : {$user->firstname} {$user->lastname}");
            }
        }

        // Responsables d'associations
        $responsables = User::role('responsable_association')->get();
        
        foreach ($responsables as $index => $user) {
            $associationIndex = $index + $gestionnaires->count();
            if ($associationIndex < $associations->count()) {
                Gestionnaires::create([
                    'association_id' => $associations[$associationIndex]->id,
                    'role_id' => $responsableRole->id,
                    'user_id' => $user->id,
                    'name' => $user->firstname . ' ' . $user->lastname,
                    'address' => 'Quartier Nlongkak, Yaoundé',
                    'phone' => $user->phone,
                    'statut' => 'Actif',
                ]);

                $this->command->info("✅ Responsable créé : {$user->firstname} {$user->lastname}");
            }
        }

        $this->command->info('✅ Tous les gestionnaires ont été créés !');
    }
}