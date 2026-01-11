<?php

namespace App\Repositories;

use App\Repositories\impl\FavouriteRepositoryInterface;
use App\Entities\Favourite;
use PDO;

class FavouriteRepository implements FavouriteRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id)
    {
        
        $sql = "SELECT * FROM favourites WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }

    public function getAllByUserId(int $userId): array
    {
        $sql = "SELECT * FROM favourite WHERE userID = :userId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $favourites = [];
        foreach ($rows as $row) {
            $favourites[] = $this->mapToEntity($row);
        }
        return $favourites;
    }

    public function save(int $userId, int $logementId): bool
    {
        $sql = "INSERT INTO favourites (userID, logementID) VALUES (:userId, :logementId)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':logementId' => $logementId
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM favourites WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    private function mapToEntity(array $row): Favourite
    {
        return new Favourite(
            (int)$row['id'],
            (int)$row['userID'],
            (int)$row['logementID']
        );
    }
}
