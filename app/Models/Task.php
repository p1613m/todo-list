<?php

namespace App\Models;

use App\Core\Abstracts\Model;

class Task extends Model
{
    public string $tableName = 'tasks';

    public array $fillable = [
        'username',
        'email',
        'text',
        'is_completed',
        'is_edited',
    ];
}