<?php

namespace App\Repositories\impl;

use App\Entities\Review;

interface ReviewRepositoryInterface
{
    public function findById(int $id);
    public function getByLogementId(int $logementId): array;
    public function getReviewOwner(int $id);
    public function save(int $userId, int $logementId, int $rating, string $comment): bool;
}
