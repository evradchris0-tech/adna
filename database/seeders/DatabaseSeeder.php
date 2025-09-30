<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Démarrage du remplissage de la base de données...');
        $this->command->newLine();

        // Ordre d'exécution important !
        $this->call([
            RolePermissionSeeder::class,  // 1. Rôles et permissions
            UserSeeder::class,             // 2. Utilisateurs
            AssociationSeeder::class,      // 3. Associations
            ParoissienSeeder::class,       // 4. Paroissiens
            GestionnaireSeeder::class,     // 5. Gestionnaires
            EngagementSeeder::class,       // 6. Engagements
            VersementSeeder::class,        // 7. Versements
            CotisationSeeder::class,       // 8. Cotisations
            OffrandeSeeder::class,         // 9. Offrandes
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Base de données remplie avec succès !');
        $this->command->newLine();
        $this->displayCredentials();
    }

    private function displayCredentials(): void
    {
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('     IDENTIFIANTS DE CONNEXION');
        $this->command->info('═══════════════════════════════════════');
        $this->command->newLine();
        
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Admin', 'admin@paroisse.cm', 'admin123'],
                ['Gestionnaire', 'marie.kouadio@paroisse.cm', 'password123'],
                ['Responsable', 'paul.atangana@paroisse.cm', 'password123'],
                ['Lecteur', 'anne.biya@paroisse.cm', 'password123'],
            ]
        );

        $this->command->newLine();
        $this->command->info('💡 Tous les autres utilisateurs utilisent : password123');
        $this->command->info('═══════════════════════════════════════');
    }
}