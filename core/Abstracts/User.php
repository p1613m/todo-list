<?php

namespace App\Core\Abstracts;

abstract class User extends Model
{
    public function passwordVerify(string $password): bool
    {
        return password_verify($password, $this->getPasswordHash());
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }
}