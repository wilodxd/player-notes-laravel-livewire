<?php

namespace App\Repositories;

use App\Models\Player;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Override;

class PlayerRepository implements PlayerRepositoryInterface
{
    #[Override]
    public function all(): Collection
    {
        return Player::all();
    }
}
