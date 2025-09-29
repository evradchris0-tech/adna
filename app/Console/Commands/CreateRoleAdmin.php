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


        $permissions = Permission::pluck('id', 'id')->all();

        $role = Role::count() > 0 ? Role::where('id',1)->first() : Role::create(['name' => 'admin']);
        $role->syncPermissions($permissions);

        if (User::count() == 0) {
            $user = User::create([
                'phone' => fake()->phoneNumber(),
                'email' => "admin@admin.com",
                'firstname' => 'admin',
                'lastname' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'password' => Hash::make('admin123')
            ]);
        }else{
            $user = User::where("email", "admin@admin.com")->first();
        }
        $user->assignRole([$role->id]);
        $this->info('user and role admin added successfully.');
    }
}
