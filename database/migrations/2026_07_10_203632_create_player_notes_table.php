<?php

use App\Models\PlayerNote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_notes', function (Blueprint $table) {
            $table->id();
            $table->string('content', PlayerNote::CONTENT_MAX_LENGTH);
            
            $table->foreignId('user_id')->constrained(); // author
            $table->foreignId('player_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_notes');
    }
};
