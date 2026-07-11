<?php

namespace Database\Seeders;

use App\Models\Player;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usernames = [
            'wilbert001',
            'carlos012',
            'M4tias',
            '_Santiago_',
            'mario_44'
        ];

        foreach($usernames as $username) {
            Player::create([
                'username' => $username
            ]);
        }
    }
}
