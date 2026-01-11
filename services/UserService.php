<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Entities\User;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(string $firstname, string $lastname, string $username, string $email, string $password, string $role): bool
    {
        return $this->userRepository->register($firstname, $lastname, $username, $email, $password, $role);
    }

    public function loginUser(string $identifier, string $password): ?User
    {
        return $this->userRepository->login($identifier, $password);
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function findUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findUserByUsername(string $username): ?User
    {
        return $this->userRepository->findByUsername($username);
    }

    public function countTotalUsers(): int
    {
        return $this->userRepository->countTotalUsers();
    }

    public function countHosts(): int
    {
        return $this->userRepository->countHosts();
    }

    public function getUserAvatarPath(int $id): string
    {
        return $this->userRepository->getAvatarPath($id);
    }
}
