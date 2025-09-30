<?php

namespace Database\Seeders;

use App\Permissions\PermissionsModel;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Réinitialiser le cache des permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('📋 Création des permissions...');

        // Récupérer les permissions depuis PermissionsModel
        $models = PermissionsModel::$models;
        $permissionsCreated = 0;

        foreach ($models as $key => $model) {
            foreach ($model as $value) {
                $permissionName = $key . "." . $value;
                
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => 'web'
                ]);
                
                $permissionsCreated++;
            }
        }

        $this->command->info("✅ {$permissionsCreated} permissions créées");
        $this->command->newLine();

        // Créer les rôles
        $this->command->info('👥 Création des rôles...');

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $gestionnaireRole = Role::firstOrCreate(['name' => 'gestionnaire', 'guard_name' => 'web']);
        $responsableRole = Role::firstOrCreate(['name' => 'responsable_association', 'guard_name' => 'web']);
        $lecteurRole = Role::firstOrCreate(['name' => 'lecteur', 'guard_name' => 'web']);

        $this->command->info('✅ 4 rôles créés');
        $this->command->newLine();

        // ADMIN : Tous les droits
        $this->command->info('🔐 Attribution des permissions au rôle ADMIN...');
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);
        $this->command->info("✅ Admin : {$allPermissions->count()} permissions");

        // GESTIONNAIRE : Droits limités
        $this->command->info('🔐 Attribution des permissions au rôle GESTIONNAIRE...');
        $gestionnairePermissions = [
            'dashboard.index',
            
            // Paroissiens
            'paroissiens.index',
            'paroissiens.show',
            'paroissiens.print',
            
            // Associations
            'association.index',
            'association.show',
            
            // Engagements
            'engagement.index',
            'engagement.create',
            'engagement.update',
            'engagement.print',
            
            // Versements
            'versement.index',
            'versement.show',
            'versement.create',
            'versement.update',
            'versement.print',
            
            // Cotisations
            'cotisations.index',
            'cotisations.show',
            'cotisations.create',
            'cotisations.update',
            'cotisations.print',
            
            // Performance
            'performance.index',
            'performance.show',
            'performance.global',
            
            // Settings
            'settings.index',
            
            // User
            'user.update.informations',
            'user.update.password',
            
            // Auth
            'auth.login',
            'auth.logout',
        ];
        
        $gestionnaireRole->syncPermissions($gestionnairePermissions);
        $this->command->info("✅ Gestionnaire : " . count($gestionnairePermissions) . " permissions");

        // RESPONSABLE ASSOCIATION : Droits consultation + offrandes
        $this->command->info('🔐 Attribution des permissions au rôle RESPONSABLE...');
        $responsablePermissions = [
            'dashboard.index',
            
            // Paroissiens
            'paroissiens.index',
            'paroissiens.show',
            
            // Associations
            'association.index',
            'association.show',
            
            // Offrandes
            'association.offrande.index',
            'association.offrande.create',
            'association.offrande.update',
            'association.offrande.print',
            
            // Engagements
            'engagement.index',
            
            // Versements
            'versement.index',
            'versement.show',
            
            // Cotisations
            'cotisations.index',
            'cotisations.show',
            
            // Performance
            'performance.index',
            'performance.show',
            
            // Settings
            'settings.index',
            
            // User
            'user.update.informations',
            'user.update.password',
            
            // Auth
            'auth.login',
            'auth.logout',
        ];
        
        $responsableRole->syncPermissions($responsablePermissions);
        $this->command->info("✅ Responsable : " . count($responsablePermissions) . " permissions");

        // LECTEUR : Consultation uniquement
        $this->command->info('🔐 Attribution des permissions au rôle LECTEUR...');
        $lecteurPermissions = [
            'dashboard.index',
            
            // Paroissiens
            'paroissiens.index',
            'paroissiens.show',
            
            // Associations
            'association.index',
            'association.show',
            
            // Offrandes
            'association.offrande.index',
            
            // Engagements
            'engagement.index',
            
            // Versements
            'versement.index',
            'versement.show',
            
            // Cotisations
            'cotisations.index',
            'cotisations.show',
            
            // Performance
            'performance.index',
            'performance.show',
            
            // Settings
            'settings.index',
            
            // User
            'user.update.informations',
            'user.update.password',
            
            // Auth
            'auth.login',
            'auth.logout',
        ];
        
        $lecteurRole->syncPermissions($lecteurPermissions);
        $this->command->info("✅ Lecteur : " . count($lecteurPermissions) . " permissions");

        $this->command->newLine();
        $this->command->info('✅ Permissions et rôles créés avec succès !');
    }
}