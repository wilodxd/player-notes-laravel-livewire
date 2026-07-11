<?php

namespace App\Repositories\Contracts;

use App\DTO\PlayerNoteData;
use App\Models\PlayerNote;
use Illuminate\Database\Eloquent\Collection;

interface PlayerNoteRepositoryInterface
{

    public function getByPlayerWithRelations(?int $playerId): Collection;

    public function create(PlayerNoteData $data): PlayerNote;

}