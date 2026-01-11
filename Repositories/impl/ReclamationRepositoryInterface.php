<?php

namespace App\Repositories\impl;

use App\Entities\Reclamation;

interface ReclamationRepositoryInterface
{
    public function findAll(): array;
    public function save(int $userId, int $logementId, string $message): bool;
}
