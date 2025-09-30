<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            // Administrateurs
            [
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'email' => 'admin@paroisse.cm',
                'phone' => '237650000001',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ],
            [
                'firstname' => 'Pascal',
                'lastname' => 'Nguema',
                'email' => 'pascal.nguema@paroisse.cm',
                'phone' => '237650000002',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'admin',
            ],

            // Gestionnaires
            [
                'firstname' => 'Marie',
                'lastname' => 'Kouadio',
                'email' => 'marie.kouadio@paroisse.cm',
                'phone' => '237670000001',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'gestionnaire',
            ],
            [
                'firstname' => 'Jean',
                'lastname' => 'Dupont',
                'email' => 'jean.dupont@paroisse.cm',
                'phone' => '237670000002',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'gestionnaire',
            ],
            [
                'firstname' => 'Sophie',
                'lastname' => 'Mbarga',
                'email' => 'sophie.mbarga@paroisse.cm',
                'phone' => '237670000003',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'gestionnaire',
            ],

            // Responsables d'associations
            [
                'firstname' => 'Paul',
                'lastname' => 'Atangana',
                'email' => 'paul.atangana@paroisse.cm',
                'phone' => '237680000001',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'responsable_association',
            ],
            [
                'firstname' => 'Grace',
                'lastname' => 'Fouda',
                'email' => 'grace.fouda@paroisse.cm',
                'phone' => '237680000002',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'responsable_association',
            ],
            [
                'firstname' => 'David',
                'lastname' => 'Essomba',
                'email' => 'david.essomba@paroisse.cm',
                'phone' => '237680000003',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'responsable_association',
            ],

            // Lecteurs
            [
                'firstname' => 'Anne',
                'lastname' => 'Biya',
                'email' => 'anne.biya@paroisse.cm',
                'phone' => '237690000001',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'lecteur',
            ],
            [
                'firstname' => 'Thomas',
                'lastname' => 'Nkolo',
                'email' => 'thomas.nkolo@paroisse.cm',
                'phone' => '237690000002',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'lecteur',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::create($userData);
            $user->assignRole($role);

            $this->command->info("✅ Utilisateur créé : {$user->email} (Rôle: {$role})");
        }

        $this->command->info('✅ Tous les utilisateurs ont été créés !');
    }
}