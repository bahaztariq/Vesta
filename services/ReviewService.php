<?php

namespace App\Services;

use App\Repositories\ReviewRepository;
use App\Entities\Review;

class ReviewService
{
    private ReviewRepository $reviewRepository;

    public function __construct(ReviewRepository $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }

    public function getReviewById(int $id): ?Review
    {
        return $this->reviewRepository->findById($id);
    }

    public function getReviewsByLogementId(int $logementId): array
    {
        return $this->reviewRepository->getByLogementId($logementId);
    }

    public function getReviewOwner(int $id): array
    {
        return $this->reviewRepository->getReviewOwner($id);
    }

    public function addReview(int $userId, int $logementId, int $rating, string $comment): bool
    {
        return $this->reviewRepository->save($userId, $logementId, $rating, $comment);
    }
}
