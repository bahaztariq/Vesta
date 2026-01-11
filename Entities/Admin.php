<?php

namespace App\Entities;

class Admin extends User {
    public function __construct(int $id, string $firstname, string $lastname, string $username, string $email, string $password) {
        parent::__construct($id, $firstname, $lastname, $username, $email, $password, 'admin');
    }
}
