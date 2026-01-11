<?php

namespace App\Repositories\impl;

use App\Entities\Notification;

interface NotificationRepositoryInterface
{
    public function findById(int $id);
    public function getByUserId(int $userId): array;
}
