<?php

namespace App\Repositories;

use App\Repositories\impl\LogementRepositoryInterface;
use App\Entities\logement;
use PDO;

class LogementRepository implements LogementRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM logements";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $logements = [];
        foreach ($rows as $row) {
            $logements[] = $this->mapToEntity($row);
        }
        return $logements;
    }

    public function getById(int $id)
    {
        $sql = "SELECT * FROM logements WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }

    public function getTopRated(int $limit = 10): array
    {
        $sql = "SELECT l.* , AVG(r.rating) as avgRating from logements l JOIN reviews r on l.id = r.logementID GROUP BY l.id order by avgRating desc limit :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $logements = [];
        foreach ($rows as $row) {
            $logements[] = $this->mapToEntity($row);
        }
        return $logements;
    }

    public function save(int $userId, string $name, string $country, string $city, float $price, string $imgPath, string $description, int $guestNum): bool
    {
        $sql = "INSERT INTO logements (HoteID, name, country, city, price, imgPath, description, guestNum) VALUES (:userId, :name, :country, :city, :price, :imgPath, :description, :guestNum)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':name' => $name,
            ':country' => $country,
            ':city' => $city,
            ':price' => $price,
            ':imgPath' => $imgPath,
            ':description' => $description,
            ':guestNum' => $guestNum
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM logements WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function update(int $id, string $name, string $country, string $city, float $price, string $imgPath, string $description): bool
    {
        $sql = "UPDATE logements SET name = :name, country = :country, city = :city, price = :price, imgPath = :imgPath, description = :description WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':country' => $country,
            ':city' => $city,
            ':price' => $price,
            ':imgPath' => $imgPath,
            ':description' => $description
        ]);
    }

    public function findByHostId(int $hostId): array
    {
        $sql = "SELECT * FROM logements WHERE HoteID = :hostId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':hostId' => $hostId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $logements = [];
        foreach ($rows as $row) {
            $logements[] = $this->mapToEntity($row);
        }
        return $logements;
    }

    private function mapToEntity(array $row): logement
    {
        return new logement(
            (int)$row['id'],
            (int)($row['HoteID'] ?? 0),
            $row['name'],
            $row['country'] ?? '',
            $row['city'],
            (float)$row['price'],
            $row['imgPath'] ?? '',
            $row['description'] ?? '',
            (int)($row['guestNum'] ?? 0)
        );
    }

    public function countTotal(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM logements");
        return (int)$stmt->fetchColumn();
    }

    public function search(string $query = '', array $filters = []): array
    {
        $sql = "SELECT * FROM logements WHERE 1=1";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (name LIKE :query OR city LIKE :query OR country LIKE :query)";
            $params[':query'] = "%$query%";
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }

        if (!empty($filters['guests'])) {
            $sql .= " AND guestNum >= :guests";
            $params[':guests'] = $filters['guests'];
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $logements = [];
        foreach ($rows as $row) {
            $logements[] = $this->mapToEntity($row);
        }
        return $logements;
    }

    public function getLogementsByHostId(int $hostId): array
    {
        $sql = "SELECT * FROM logements WHERE HoteID = :hostId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':hostId' => $hostId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $logements = [];
        foreach ($rows as $row) {
            $logements[] = $this->mapToEntity($row);
        }
        return $logements;
    }
    public function getLogementsDates(int $logementId): array
    {
        $sql = "SELECT startDate, endDate FROM reservations WHERE logementID = :logementId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':logementId' => $logementId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dates = [];
        foreach ($rows as $row) {
            $dates[] = [
                'startDate' => $row['startDate'],
                'endDate' => $row['endDate']
            ];
        }
        return $dates;
    }
}
