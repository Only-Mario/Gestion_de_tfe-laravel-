<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
class CreateAdmin extends Seeder
{
    /**
     * Run the database seeds.
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
