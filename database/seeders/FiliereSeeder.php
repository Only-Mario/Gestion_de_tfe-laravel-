<?php

namespace Database\Seeders;

use App\Models\Filiere;
use Illuminate\Database\Seeder;


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
            ['nom' => 'BTPCD', 'description' => ' Bâtiment, Travaux Publics et Construction Durable'],
            ['nom' => 'FC', 'description' => ' Froid et Climatisation'],
        ];

        foreach ($filieres as $filiere) {
            Filiere::create($filiere);
        }
    }
}
