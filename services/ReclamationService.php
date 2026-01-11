<?php

namespace App\Services;

use App\Repositories\ReclamationRepository;
use App\Entities\Reclamation;

class ReclamationService
{
    private ReclamationRepository $reclamationRepository;

    public function __construct(ReclamationRepository $reclamationRepository)
    {
        $this->reclamationRepository = $reclamationRepository;
    }

    public function getAllReclamations(): array
    {
        return $this->reclamationRepository->findAll();
    }

    public function createReclamation(int $userId, int $logementId, string $message): bool
    {
        return $this->reclamationRepository->save($userId, $logementId, $message);
    }
}
