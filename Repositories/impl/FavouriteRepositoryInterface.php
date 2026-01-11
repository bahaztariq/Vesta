<?php

namespace App\Repositories\impl;

use App\Entities\Favourite;

interface FavouriteRepositoryInterface
{
    public function findById(int $id);
    public function getAllByUserId(int $userId): array;
    public function save(int $userId, int $logementId): bool;
    public function delete(int $id): bool;
}
