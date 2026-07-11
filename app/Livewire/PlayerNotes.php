<?php

namespace App\Livewire;

use App\DTO\PlayerNoteData;
use App\Models\PlayerNote;
use App\Repositories\Contracts\PlayerNoteRepositoryInterface;
use App\Repositories\Contracts\PlayerRepositoryInterface;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PlayerNotes extends Component
{

    #[Validate('required|string|max:' . PlayerNote::CONTENT_MAX_LENGTH)]
    public string $content = '';

    #[Validate('required|exists:players,id')]
    public string $playerId = '';

    public string $filterPlayerId = '';

    protected PlayerNoteRepositoryInterface $notes;

    protected PlayerRepositoryInterface $players;

    public function boot(PlayerNoteRepositoryInterface $notes, PlayerRepositoryInterface $players): void {
        $this->notes = $notes;
        $this->players = $players;
    }

    public function render()
    {
        return view('livewire.player-notes', [
            'players' => $this->players->all(),
            'notes' => $this->notes->getByPlayerWithRelations(
                $this->filterPlayerId !== '' ? (int) $this->filterPlayerId : null
            )
        ]);
    }

    public function save(): void {

        $this->authorize('create', PlayerNote::class);

        $this->validate();

        $data = new PlayerNoteData(
            $this->content,
            (int) $this->playerId,
            auth()->id()
        );

        $this->notes->create($data);

        $this->reset('content', 'playerId');

        $this->dispatch('modal-close', name: 'add-note');
    }

}
