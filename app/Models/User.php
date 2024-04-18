<?php

namespace App\Models;

use Core\Database\Model;

class User extends Model
{
    protected array $hidden = ['password'];
    protected array $casts = [
        'name' => 'collection',
        'created_at' => 'datetime'
    ];
}
