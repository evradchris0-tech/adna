<?php

namespace Database\Seeders;

use App\Models\Associations;
use App\Models\Paroissien;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ParoissienSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $associations = Associations::all();

        if ($associations->isEmpty()) {
            $this->command->error('❌ Aucune association trouvée. Exécutez AssociationSeeder d\'abord.');
            return;
        }

        // Prénoms camerounais courants
        $prenomsHommes = ['Paul', 'Jean', 'Pierre', 'Emmanuel', 'David', 'Michel', 'Joseph', 'André', 'Thomas', 'Daniel', 'Martin', 'François', 'Patrick', 'Henri', 'Eric', 'Alain', 'Bernard', 'Claude', 'Georges', 'Luc'];
        $prenomsFemmes = ['Marie', 'Sophie', 'Grace', 'Anne', 'Catherine', 'Jeanne', 'Rose', 'Claire', 'Françoise', 'Beatrice', 'Sylvie', 'Agnes', 'Monique', 'Nicole', 'Christine', 'Marguerite', 'Hélène', 'Pascale', 'Denise', 'Isabelle'];
        
        // Noms de famille camerounais
        $noms = ['Ngono', 'Atangana', 'Fouda', 'Mballa', 'Essomba', 'Nkolo', 'Biya', 'Eto\'o', 'Milla', 'Abega', 'Onana', 'Makoun', 'Song', 'Bassong', 'Wome', 'Kameni', 'Emana', 'Matip', 'Chedjou', 'Assou-Ekotto', 'Nguema', 'Mbarga', 'Kouadio', 'Owona', 'Ebogo'];

        // Villes camerounaises
        $villes = ['Yaoundé', 'Douala', 'Bafoussam', 'Garoua', 'Bamenda', 'Maroua', 'Ngaoundéré', 'Bertoua', 'Ebolowa', 'Kribi'];

        // Quartiers de Yaoundé
        $quartiers = ['Bastos', 'Nlongkak', 'Mvog-Ada', 'Melen', 'Essos', 'Emana', 'Ekounou', 'Nkol-Eton', 'Mokolo', 'Briqueterie'];

        // Niveaux d'étude
        $niveauxEtude = ['Primaire', 'Secondaire', 'Universitaire', 'Sans niveau'];

        // Catégories
        $categories = ['membre', 'ancien', 'diacre'];

        // Situations
        $situations = ['Employer', 'Chomeur', 'Etudiant', 'Retraite'];

        // Métiers
        $metiers = ['Enseignant', 'Médecin', 'Ingénieur', 'Commerçant', 'Fonctionnaire', 'Entrepreneur', 'Agriculteur', 'Artisan', 'Chauffeur', 'Infirmier'];

        $matriculeCounter = 1;

        for ($i = 1; $i <= 100; $i++) {
            $genre = $faker->randomElement(['h', 'f']);
            $prenom = $genre === 'h' ? $faker->randomElement($prenomsHommes) : $faker->randomElement($prenomsFemmes);
            $nom = $faker->randomElement($noms);
            
            $situation = $faker->randomElement($situations);
            $job = $situation === 'Employer' ? $faker->randomElement($metiers) : null;
            $jobPoste = $situation === 'Employer' ? $faker->jobTitle() : null;
            $servicePlace = $situation === 'Employer' ? $faker->company() : null;

            $maritalStatus = $faker->randomElement(['c', 'm', 'v']);
            $nbChildren = $maritalStatus === 'm' ? $faker->numberBetween(0, 7) : 0;

            $birthdate = $faker->dateTimeBetween('-70 years', '-18 years');
            $baptiseDate = (clone $birthdate)->modify('+' . $faker->numberBetween(1, 10) . ' years');
            $confirmDate = $faker->boolean(70) ? (clone $baptiseDate)->modify('+' . $faker->numberBetween(5, 15) . ' years') : null;
            $adhesionDate = $faker->boolean(80) ? (clone $baptiseDate)->modify('+' . $faker->numberBetween(10, 20) . ' years') : null;

            $oldMatricule = sprintf('OLD%04d', $matriculeCounter);
            $newMatricule = Paroissien::getMatricule();

            Paroissien::create([
                'firstname' => $prenom,
                'lastname' => $nom,
                'genre' => $genre,
                'birthdate' => $birthdate->format('Y-m-d'),
                'birthplace' => $faker->randomElement($villes),
                'email' => strtolower($prenom . '.' . $nom . '@email.cm'),
                'school_level' => $faker->randomElement($niveauxEtude),
                'address' => 'Quartier ' . $faker->randomElement($quartiers) . ', Yaoundé',
                'phone' => '237' . $faker->numberBetween(650000000, 699999999),
                'old_matricule' => $oldMatricule,
                'new_matricule' => $newMatricule,
                'association_id' => $associations->random()->id,
                'categorie' => $faker->randomElement($categories),
                'confirm_date' => $confirmDate ? $confirmDate->format('Y-m-d') : null,
                'adhesion_date' => $adhesionDate ? $adhesionDate->format('Y-m-d') : null,
                'baptise_date' => $baptiseDate->format('Y-m-d'),
                'father_name' => $faker->randomElement($prenomsHommes) . ' ' . $faker->randomElement($noms),
                'mother_name' => $faker->randomElement($prenomsFemmes) . ' ' . $faker->randomElement($noms),
                'wife_or_husban_name' => $maritalStatus === 'm' ? ($genre === 'h' ? $faker->randomElement($prenomsFemmes) : $faker->randomElement($prenomsHommes)) . ' ' . $faker->randomElement($noms) : '',
                'marital_status' => $maritalStatus,
                'nb_children' => $nbChildren,
                'job' => $job,
                'job_poste' => $jobPoste,
                'service_place' => $servicePlace,
                'situation' => $situation,
            ]);

            $matriculeCounter++;

            if ($i % 10 === 0) {
                $this->command->info("✅ {$i} paroissiens créés...");
            }
        }

        $this->command->info('✅ Tous les paroissiens ont été créés !');
    }
}