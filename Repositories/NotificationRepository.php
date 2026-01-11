<?php

namespace App\Repositories;

use App\Repositories\impl\NotificationRepositoryInterface;
use App\Entities\Notification;
use PDO;

class NotificationRepository implements NotificationRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findById(int $id)
    {
        $sql = "SELECT * FROM notifications WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }

    public function getByUserId(int $userId): array
    {
        $sql = "SELECT * FROM notifications WHERE userID = :userId ORDER BY date DESC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notifications = [];
        foreach ($rows as $row) {
            $notifications[] = $this->mapToEntity($row);
        }
        return $notifications;
    }

    private function mapToEntity(array $row): Notification
    {
        return new Notification(
            (int)$row['id'],
            (int)$row['userID'],
            $row['message'],
            $row['date'] ?? null
        );
    }
}
