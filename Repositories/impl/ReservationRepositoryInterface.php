<?php

namespace App\Repositories\impl;

use App\Entities\Reservation;

interface ReservationRepositoryInterface
{
    public function save(int $userId, int $logementId, string $startDate, string $endDate, int $price): bool;
    public function delete(int $id): bool;
}
