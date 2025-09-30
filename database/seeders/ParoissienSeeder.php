<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ParoissienSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('paroissiens')->insert([
            [
                'firstname' => 'Jean',
                'lastname' => 'Dupont',
                'genre' => 'Homme',
                'birthdate' => '1990-05-10',
                'birthplace' => 'Abidjan',
                'email' => 'jean.dupont@mail.com',
                'school_level' => 'Licence',
                'address' => 'Cocody',
                'phone' => '0700112233',
                'old_matricule' => 'M123',
                'new_matricule' => 'N123',
                'association_id' => 1,
                'categorie' => 'Membre actif',
                'father_name' => 'Pierre Dupont',
                'mother_name' => 'Marie Dupont',
                'wife_or_husban_name' => 'Anne Dupont',
                'marital_status' => 'Marié',
                'nb_children' => '2',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'firstname' => 'Marie',
                'lastname' => 'Kouadio',
                'genre' => 'Femme',
                'birthdate' => '1985-08-15',
                'birthplace' => 'Yopougon',
                'email' => 'marie.kouadio@mail.com',
                'school_level' => 'Master',
                'address' => 'Yopougon',
                'phone' => '0700998877',
                'old_matricule' => 'M456',
                'new_matricule' => 'N456',
                'association_id' => 2,
                'categorie' => 'Catéchiste',
                'father_name' => 'Jacques Kouadio',
                'mother_name' => 'Clémentine Kouadio',
                'wife_or_husban_name' => 'Paul Kouadio',
                'marital_status' => 'Mariée',
                'nb_children' => '3',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
