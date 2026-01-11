<?php

namespace App\Entities;

use PDO;

class User {
    
    private int $id;
    private string $firstname;
    private string $lastname;
    private string $username;
    private string $email;
    private string $password;
    private string $role; 
    private ?string $avatar;

    public function __construct(int $id, string $firstname, string $lastname, string $username, string $email, string $password, string $role, ?string $avatar = null) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->avatar = $avatar;
    }

    public function getId(): int { return $this->id; }
    public function getRole(): string { return $this->role; }
    public function getFullName(): string { return $this->firstname . ' ' . $this->lastname; }
    public function getPassword(): string { return $this->password; }
    public function getEmail(): string { return $this->email;}
    public function getUsername(): string { return $this->username; }
    public function getAvatar(): ?string { return $this->avatar; }


    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isHost(): bool {
        return $this->role === 'host';
    }
}