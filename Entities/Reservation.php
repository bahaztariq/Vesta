<?php

namespace App\Entities;
use PDO;

class Reservation{
    private int $id;
    private int $userId; // Added to match repo/schema usage
    private int $logementId; // Added to match repo/schema usage
    private string $startDate;
    private string $endDate;
    private int $price;
    
    public function __construct(int $id, int $userId, int $logementId, string $startDate, string $endDate, int $price){
        $this->id = $id;
        $this->userId = $userId;
        $this->logementId = $logementId;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->price = $price;
    }

    public function getId(): int { return $this->id; }
    public function getUserId(): int { return $this->userId; }
    public function getLogementId(): int { return $this->logementId; }
    public function getStartDate(): string { return $this->startDate; }
    public function getEndDate(): string { return $this->endDate; }
    public function getPrice(): int { return $this->price; }
}
