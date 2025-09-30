<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class CreateRoleAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Créer les roles par defaut';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 1. Récupérer toutes les permissions
        $permissions = Permission::pluck('id', 'id')->all();

        // 2. Créer ou récupérer le rôle admin
        $role = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        
        // 3. Synchroniser toutes les permissions avec le rôle admin
        $role->syncPermissions($permissions);

        $this->info('Role admin créé avec ' . count($permissions) . ' permissions.');

        // 4. Créer ou récupérer l'utilisateur admin
        $user = User::where('email', 'admin@admin.com')->first();

        if (!$user) {
            // L'utilisateur n'existe pas, on le crée
            $user = User::create([
                'phone' => '237' . rand(600000000, 699999999),
                'email' => 'admin@admin.com',
                'firstname' => 'Admin',
                'lastname' => 'Système',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'password' => Hash::make('admin123')
            ]);
            
            $this->info('Utilisateur admin créé avec succès.');
            $this->info('Email: admin@admin.com');
            $this->info('Mot de passe: admin123');
        } else {
            $this->info('Utilisateur admin déjà existant.');
        }

        // 5. Assigner le rôle admin à l'utilisateur
        if (!$user->hasRole('admin')) {
            $user->assignRole($role);
            $this->info('Rôle admin assigné à l\'utilisateur.');
        } else {
            $this->info('L\'utilisateur possède déjà le rôle admin.');
        }

        $this->info('✅ Configuration terminée avec succès !');
    }
}