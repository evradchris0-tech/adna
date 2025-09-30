<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class AssociationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('associations')->insert([
            ['name' => 'Chorale Sainte Cécile', 'sigle' => 'CSC', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Jeunesse Paroissiale', 'sigle' => 'JP', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
