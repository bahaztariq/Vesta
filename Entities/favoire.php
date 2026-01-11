<?php

namespace App\Entities;
use PDO;

class Favourite{
    private int $id;
    private int $userId;
    private int $logementId;
    
    public function __construct(int $id, int $userId, int $logementId){
        $this->id = $id;
        $this->userId = $userId;
        $this->logementId = $logementId;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getLogementId(): int { return $this->logementId; }
}