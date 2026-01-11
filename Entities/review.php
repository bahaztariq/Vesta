<?php

namespace App\Entities;
use PDO;

class Review{
    private int $id;
    private int $userId;
    private int $logementId;
    private int $rating;
    private string $comment;
    
    public function __construct(int $id, int $userId, int $logementId, int $rating, string $comment)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->logementId = $logementId;
        $this->rating = $rating;
        $this->comment = $comment;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getLogementId(): int { return $this->logementId; }
    public function getRating(): int { return $this->rating; }
    public function getComment(): string { return $this->comment; }
}