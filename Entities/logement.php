<?php

namespace App\Entities;
use PDO;

class logement{
    private int $id;
    private int $hoteId;
    private string $name;
    private string $country;
    private string $city;
    private float $price;
    private string $imgPath;
    private string $description;
    private int $guestNum; 

    public function __construct(int $id, int $hoteId, string $name, string $country, string $city, float $price, string $imgPath, string $description, int $guestNum)
    {
        $this->id = $id;
        $this->hoteId = $hoteId;
        $this->name = $name;
        $this->country = $country;
        $this->city = $city;
        $this->price = $price;
        $this->imgPath = $imgPath;
        $this->description = $description;
        $this->guestNum = $guestNum;
    }

    // Getters
    public function getId(): int { return $this->id; }
    public function getHoteId(): int { return $this->hoteId; }
    public function getName(): string { return $this->name; }
    public function getCountry(): string { return $this->country; }
    public function getCity(): string { return $this->city; }
    public function getPrice(): float { return $this->price; }
    public function getImgPath(): string { return $this->imgPath; }
    public function getDescription(): string { return $this->description; }
    public function getGuestNum(): int { return $this->guestNum; }
}