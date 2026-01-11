<?php

namespace App\Repositories\impl;

use App\Entities\User;

interface UserRepositoryInterface
{
    public function getUserById(int $id);
    public function findByEmail(string $email);
    public function findByUsername(string $username);
    public function register(string $firstname, string $lastname, string $username, string $email, string $password, string $role): bool;
    public function login(string $identifier, string $password): ?User;
    public function countTotalUsers(): int;
    public function countHosts(): int;
    public function getAvatarPath(int $id): string;
}
