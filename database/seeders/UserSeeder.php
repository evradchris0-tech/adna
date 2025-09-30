<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'firstname' => 'Admin',
            'lastname' => 'Principal',
            'email' => 'admin@paroisse.com',
            'phone' => '0102030405',
            'password' => Hash::make('password'),
        ]);
    }
}
