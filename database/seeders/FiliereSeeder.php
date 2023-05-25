<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;


class FiliereSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filieres = [
            ['nom' => 'GEI', 'description' => 'Génie électrique et informatique'],
            ['nom' => 'GC', 'description' => ' Génie Civil'],
            ['nom' => 'MS', 'description' => ' Maintenance des Systèmes'],
            ['nom' => 'GE', 'description' => ' Génie Energétique'],
            ['nom' => 'GMP', 'description' => ' Génie Mécanique et Productique'],
            // Ajoutez d'autres filières ici
        ];

        foreach ($filieres as $filiere) {
            Filiere::create($filiere);
        }
    }
}
