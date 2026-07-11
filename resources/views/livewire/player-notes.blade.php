<div>
    <h1 class="text-3xl uppercase font-black text-center mb-12">Notas Jugadores</h1>

    <div class="flex items-end justify-between gap-4 mb-4">
        <flux:select wire:model.live="filterPlayerId" label="Filtrar por jugador" placeholder="Todos los jugadores" class="max-w-xs">
            @foreach ($players as $player)
                <flux:select.option value="{{ $player->id }}">
                    {{ $player->username }}
                </flux:select.option>
            @endforeach
        </flux:select>

        @can('create', App\Models\PlayerNote::class)
            <flux:modal.trigger name="add-note">
                <flux:button variant="primary">
                    Nueva Nota
                </flux:button>
            </flux:modal.trigger>
        @endcan
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Jugador</flux:table.column>
            <flux:table.column>Nota</flux:table.column>
            <flux:table.column>Autor</flux:table.column>
            <flux:table.column>Fecha</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>

            @foreach ($notes as $note)
                <flux:table.row wire:key="note-{{ $note->id }}">
                    <flux:table.cell>{{ $note->player->username }}</flux:table.cell>
                    <flux:table.cell>{{ $note->content }}</flux:table.cell>
                    <flux:table.cell>{{ $note->author->name }}</flux:table.cell>
                    <flux:table.cell>{{ $note->created_at->diffForHumans() }}</flux:table.cell>
                </flux:table.row>
            @endforeach

        </flux:table.rows>

    </flux:table>


    <flux:modal name="add-note" variant="flyout">

        <div class="p-4 max-w-lg mt-8">
            <h2 class="text-xl mb-4 uppercase font-black">nueva nota</h2>
            <form wire:submit="save">
                <flux:select wire:model="playerId" label="Jugador" placeholder="-- Selecciona un Jugador --" class="mb-4">
                    @foreach ($players as $player)
                        <flux:select.option value="{{ $player->id }}">
                            {{ $player->username }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:textarea wire:model="content" label="Nota" placeholder="Note Content..." class="mb-4"/>

                <flux:button type="submit" variant="primary" class="block! ml-auto">
                    Guardar
                </flux:button>
            </form>
        </div>
    
    </flux:modal>

</div>
