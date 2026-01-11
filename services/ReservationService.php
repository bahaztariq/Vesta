<?php

namespace App\Services;

use App\Repositories\ReservationRepository;
use App\Entities\Reservation;

class ReservationService
{
    private ReservationRepository $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function createReservation(int $userId, int $logementId, string $startDate, string $endDate, int $price): bool
    {
        return $this->reservationRepository->save($userId, $logementId, $startDate, $endDate, $price);
    }

    public function deleteReservation(int $id): bool
    {
        return $this->reservationRepository->delete($id);
    }

    public function getReservationsByUserId(int $userId): array
    {
        return $this->reservationRepository->getByUserId($userId);
    }
}
