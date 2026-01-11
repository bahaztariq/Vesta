<?php

namespace App\Repositories;

use App\Repositories\impl\ReclamationRepositoryInterface;
use App\Entities\Reclamation;
use PDO;

class ReclamationRepository implements ReclamationRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM reclamations";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reclamations = [];
        foreach ($rows as $row) {
            $reclamations[] = $this->mapToEntity($row);
        }
        return $reclamations;
    }

    public function save(int $userId, int $logementId, string $message): bool
    {
        $sql = "INSERT INTO reclamations (userID, logementID, message) VALUES (:userId, :logementId, :message)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':logementId' => $logementId,
            ':message' => $message
        ]);
    }

    private function mapToEntity(array $row): Reclamation
    {
        return new Reclamation(
            (int)$row['id'],
            (int)$row['userID'],
            (int)$row['logementID'],
            $row['message']
        );
    }
}
