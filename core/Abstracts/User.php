<?php

namespace App\Core\Abstracts;

abstract class User extends Model
{
    /**
     * Password verify
     *
     * @param string $password
     * @return bool
     */
    public function passwordVerify(string $password): bool
    {
        return password_verify($password, $this->getPasswordHash());
    }

    /**
     * Get password hash
     *
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->password;
    }
}