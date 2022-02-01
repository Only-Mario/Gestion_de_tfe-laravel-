<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user=[
            'name'=>'Admin',
            'password'=>bcrypt('institfeadmin'),
            'email'=>'institfeadmin@gmail.com',
            'is_admin'=>true,
         ];
         User::create($user);
    }
}
