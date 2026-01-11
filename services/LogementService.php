<?php

namespace App\Services;

use App\Repositories\LogementRepository;
use App\Entities\logement;

class LogementService
{
    private LogementRepository $logementRepository;

    public function __construct(LogementRepository $logementRepository)
    {
        $this->logementRepository = $logementRepository;
    }

    public function getAllLogements(): array
    {
        return $this->logementRepository->findAll();
    }

    public function getLogementById(int $id): ?logement
    {
        return $this->logementRepository->getById($id);
    }

    public function getTopRatedLogements(int $limit = 10): array
    {
        return $this->logementRepository->getTopRated($limit);
    }

    public function createLogement(int $userId, string $name, string $country, string $city, float $price, string $imgPath, string $description, int $guestNum): bool
    {
        return $this->logementRepository->save($userId, $name, $country, $city, $price, $imgPath, $description, $guestNum);
    }

    public function deleteLogement(int $id): bool
    {
        return $this->logementRepository->delete($id);
    }

    public function updateLogement(int $id, string $name, string $country, string $city, float $price, string $imgPath, string $description): bool
    {
        return $this->logementRepository->update($id, $name, $country, $city, $price, $imgPath, $description);
    }

    public function getLogementsByHost(int $hostId): array
    {
        return $this->logementRepository->findByHostId($hostId);
    }

    public function countTotalLogements(): int
    {
        return $this->logementRepository->countTotal();
    }

    public function searchLogements(string $query = '', array $filters = []): array
    {
        return $this->logementRepository->search($query, $filters);
    }

    public function getLogementsDates(int $logementId): array
    {
        return $this->logementRepository->getLogementsDates($logementId);
    }

    
}
