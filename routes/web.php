<?php

use App\Livewire\PlayerNotes;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/', PlayerNotes::class)->name('player-notes');
});

require __DIR__.'/settings.php';
