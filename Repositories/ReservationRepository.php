<?php

namespace App\Repositories;

use App\Repositories\impl\ReservationRepositoryInterface;
use App\Entities\Reservation;
use PDO;

class ReservationRepository implements ReservationRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(int $userId, int $logementId, string $startDate, string $endDate, int $price): bool
    {
        
        $sql = "INSERT INTO reservations (userID, logmentID, startDate, endDate, price) VALUES (:userId, :logementId, :startDate, :endDate, :price)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':logementId' => $logementId,
            ':startDate' => $startDate,
            ':endDate' => $endDate,
            ':price' => $price
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM reservations WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getByUserId(int $userId): array
    {
        $sql = "SELECT * FROM reservations WHERE userID = :userId ORDER BY startDate DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reservations = [];
        foreach ($rows as $row) {
            $reservations[] = $this->mapToEntity($row); 
        }
        return $reservations;
    }

    private function mapToEntity(array $row): Reservation
    {
        return new Reservation(
            (int)$row['id'],
            (int)$row['userID'],
            (int)$row['logmentID'], 
            $row['startDate'],
            $row['endDate'],
            (int)$row['price']
        );
    }
   
}
