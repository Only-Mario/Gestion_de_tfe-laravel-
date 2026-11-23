<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;use Illuminate\Support\Str;

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
            'password'=>bcrypt(env('ADMIN_PASSWORD') ?: Str::random(32)),
            'email'=>'institfeadmin@gmail.com',
        ];
        $admin = new User($user);
        $admin->is_admin = true;
        $admin->save();
    }
}
