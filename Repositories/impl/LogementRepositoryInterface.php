<?php

namespace App\Repositories\impl;

use App\Entities\logement;

interface LogementRepositoryInterface
{
    public function findAll(): array;
    public function getById(int $id);
    public function getTopRated(int $limit = 10): array;
    public function save(int $userId, string $name, string $country, string $city, float $price, string $imgPath, string $description, int $guestNum): bool;
    public function delete(int $id): bool;
    public function update(int $id, string $name, string $country, string $city, float $price, string $imgPath, string $description): bool;
    public function findByHostId(int $hostId): array;
    public function countTotal(): int;
    public function search(string $query = '', array $filters = []): array;
}
