<?php

namespace App\Models;

use Core\Database\Model;

class User extends Model
{
    /**
     * Hidden attributes
     */
    protected array $hidden = ['password'];

    /**
     * Casts
     */
    protected array $casts = [
        'created_at' => 'datetime'
    ];

    /**
     * Elements that can be filled
     */
    protected array $fillable = ['name', 'email', 'password'];
}
