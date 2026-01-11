<?php

namespace App\Entities;

class Reclamation
{
    private int $id;
    private int $userId;
    private int $logementId;
    private string $message;

    public function __construct(int $id, int $userId, int $logementId, string $message)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->logementId = $logementId;
        $this->message = $message;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getLogementId(): int { return $this->logementId; }
    public function getMessage(): string { return $this->message; }
}
