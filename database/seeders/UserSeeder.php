<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make User
        User::create([
            'name' => 'soporte',
            'email' => 'soporte@correo.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Support,
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'invitado',
            'email' => 'invitado@correo.com',
            'password' => bcrypt('password'),
            'role' => UserRole::Viewer,
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
