<?php

namespace App\Repositories;

use App\Repositories\impl\UserRepositoryInterface;
use App\Entities\User;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function register(string $firstname, string $lastname, string $username, string $email, string $password, string $role): bool
    {
         // Check if user exists
         if ($this->findByEmail($email) || $this->findByUsername($username)) {
            return false;
        }

        $sql = "INSERT INTO Users (FirstName, LastName, UserName, Email, Password, roles) VALUES (:firstname, :lastname, :username, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => $role
        ]);
    }

    public function login(string $identifier, string $password): ?User
    {
        $user = $this->findByEmail($identifier);
        if (!$user) {
            $user = $this->findByUsername($identifier);
        }

        if ($user && password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    public function getUserById(int $id)
    {
        $sql = "SELECT * FROM Users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }

    public function findByEmail(string $email)
    {
        $sql = "SELECT * FROM Users WHERE Email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }

    public function findByUsername(string $username)
    {
        $sql = "SELECT * FROM Users WHERE UserName = :username";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $this->mapToEntity($row);
        }
        return null;
    }



    private function mapToEntity(array $row): User
    {
        return new User(
            (int)$row['id'],
            $row['FirstName'],
            $row['LastName'],
            $row['UserName'],
            $row['Email'],
            $row['Password'],
            $row['roles'] ?? 'Voyageur',
            $row['Avatar'] ?? null
        );
    }

    public function countTotalUsers(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Users");
        return (int)$stmt->fetchColumn();
    }

    public function countHosts(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM Users WHERE roles = 'Hote'");
        return (int)$stmt->fetchColumn();
    }

    public function getAvatarPath(int $id): string
    {
        $user = $this->getUserById($id); 
        
        if ($user && $user->getAvatar()) {
            return $user->getAvatar();
        }

        if ($user) {
            $name = urlencode($user->getFullName());
            return "https://ui-avatars.com/api/?name={$name}&background=random";
        }
        
    
        return "https://ui-avatars.com/api/?name=User&background=random";
    }
}
