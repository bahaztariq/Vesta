<?php

namespace App\Services;

use App\Repositories\FavouriteRepository; // Now using correct namespace
use App\Entities\Favourite;

class FavouriteService
{
    private FavouriteRepository $favouriteRepository;

    public function __construct(FavouriteRepository $favouriteRepository)
    {
        $this->favouriteRepository = $favouriteRepository;
    }

    public function getFavouriteById(int $id): ?Favourite
    {
        return $this->favouriteRepository->findById($id);
    }

    public function getFavouritesByUserId(int $userId): array
    {
        return $this->favouriteRepository->getAllByUserId($userId);
    }

    public function addFavourite(int $userId, int $logementId): bool
    {
        return $this->favouriteRepository->save($userId, $logementId);
    }

    public function removeFavourite(int $id): bool
    {
        return $this->favouriteRepository->delete($id);
    }
}
