<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PlayerRepositoryInterface
{
    public function all(): Collection;
}
