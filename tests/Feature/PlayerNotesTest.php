<?php

use App\Livewire\PlayerNotes;
use App\Models\Player;
use App\Models\PlayerNote;
use App\Models\User;
use Livewire\Livewire;

test('support user can save a note and it is stored in the database', function () {
    $support = User::factory()->support()->create();
    $player = Player::factory()->create();

    $this->actingAs($support);

    Livewire::test(PlayerNotes::class)
        ->set('playerId', (string) $player->id)
        ->set('content', 'Great performance today.')
        ->call('save')
        ->assertHasNoErrors();

    expect(PlayerNote::query()
        ->where('player_id', $player->id)
        ->where('user_id', $support->id)
        ->where('content', 'Great performance today.')
        ->exists()
    )->toBeTrue();
});

test('empty content fails validation and nothing is stored', function () {
    $support = User::factory()->support()->create();
    $player = Player::factory()->create();

    $this->actingAs($support);

    Livewire::test(PlayerNotes::class)
        ->set('playerId', (string) $player->id)
        ->set('content', '')
        ->call('save')
        ->assertHasErrors(['content' => 'required']);

    expect(PlayerNote::count())->toBe(0);
});

test('content longer than the max length fails validation', function () {
    $support = User::factory()->support()->create();
    $player = Player::factory()->create();

    $this->actingAs($support);

    Livewire::test(PlayerNotes::class)
        ->set('playerId', (string) $player->id)
        ->set('content', str_repeat('a', PlayerNote::CONTENT_MAX_LENGTH + 1))
        ->call('save')
        ->assertHasErrors(['content' => 'max']);

    expect(PlayerNote::count())->toBe(0);
});

test('viewer user is forbidden from saving a note', function () {
    $viewer = User::factory()->viewer()->create();
    $player = Player::factory()->create();

    $this->actingAs($viewer);

    Livewire::test(PlayerNotes::class)
        ->set('playerId', (string) $player->id)
        ->set('content', 'Trying to sneak a note in.')
        ->call('save')
        ->assertForbidden();

    expect(PlayerNote::count())->toBe(0);
});

test('only support users see the add note button', function () {
    $support = User::factory()->support()->create();
    $viewer = User::factory()->viewer()->create();

    $this->actingAs($support)
        ->get('/')
        ->assertSee('Nueva Nota');

    $this->actingAs($viewer)
        ->get('/')
        ->assertDontSee('Nueva Nota');
});
