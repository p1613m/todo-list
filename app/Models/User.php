<?php

namespace App\Models;

use \App\Core\Abstracts\User as AbstractUser;

class User extends AbstractUser
{
    public string $tableName = 'users';
    public array $fillable = [
        'login',
        'password',
    ];
}