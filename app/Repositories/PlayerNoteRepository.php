<?php

namespace App\Repositories;

use App\DTO\PlayerNoteData;
use App\Models\PlayerNote;
use App\Repositories\Contracts\PlayerNoteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Override;

class PlayerNoteRepository implements PlayerNoteRepositoryInterface
{

    #[Override]
    public function getByPlayerWithRelations(?int $playerId): Collection
    {
        return PlayerNote::with(['author', 'player'])
            ->when($playerId, fn ($query) => $query->where('player_id', $playerId))
            ->latest()
            ->get();
    }

    #[Override]
    public function create(PlayerNoteData $data): PlayerNote
    {
        return PlayerNote::create([
            'content' => $data->content,
            'player_id' => $data->playerId,
            'user_id' => $data->userId
        ]);
    }

}