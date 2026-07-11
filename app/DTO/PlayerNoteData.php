<?php

namespace App\DTO;

final readonly class PlayerNoteData 
{

    public function __construct(
        public string $content,
        public int $playerId,
        public int $userId
    ){
           
    }

}