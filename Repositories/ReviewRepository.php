<?php

namespace App\Repositories;

use App\Repositories\impl\ReviewRepositoryInterface;
use App\Entities\Review;
use PDO;

class ReviewRepository implements ReviewRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM reviews WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }

    public function getByLogementId(int $logementId): array
    {
        $sql = "SELECT * FROM reviews WHERE logementID = :logementId ORDER BY date_ DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':logementId' => $logementId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reviews = [];
        foreach ($rows as $row) {
            $reviews[] = $this->mapToEntity($row);
        }
        return $reviews;
    }

    public function getReviewOwner(int $id)
    {

        $sql = "SELECT * FROM Users WHERE id = (SELECT hostID FROM reviews WHERE id = :id)"; 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(int $userId, int $logementId, int $rating, string $comment): bool
    {
        $sql = "INSERT INTO reviews (userID, logementID, rating, comment) VALUES (:userId, :logementId, :rating, :comment)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':logementId' => $logementId,
            ':rating' => $rating,
            ':comment' => $comment
        ]);
    }

    private function mapToEntity(array $row): Review
    {
        return new Review(
            (int)$row['id'],
            (int)$row['userID'],
            (int)$row['logementID'],
            (int)$row['rating'],
            $row['comment']
        );
    }
}
