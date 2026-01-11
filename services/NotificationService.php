<?php

namespace App\Services;

use App\Repositories\NotificationRepository;
use App\Entities\Notification;

class NotificationService
{
    private NotificationRepository $notificationRepository;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
    }

    public function getNotificationById(int $id): ?Notification
    {
        return $this->notificationRepository->findById($id);
    }

    public function getNotificationsByUserId(int $userId): array
    {
        return $this->notificationRepository->getByUserId($userId);
    }
}
