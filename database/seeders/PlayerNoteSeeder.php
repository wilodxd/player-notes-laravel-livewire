<?php

namespace Database\Seeders;

use App\Models\Player;
use App\Models\PlayerNote;
use App\Models\User;
use Illuminate\Database\Seeder;

class PlayerNoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $players = Player::all();

        User::all()->each(function (User $user) use ($players) {
            PlayerNote::factory()
                ->count(random_int(0, 10))
                ->for($user, 'author')
                ->recycle($players)
                ->create();
        });
    }
}
