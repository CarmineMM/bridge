<?php

namespace Code\Foundation\Auth;

use Core\Database\Model;

class Authentication extends Model
{
    /**
     * Undocumented variable
     *
     * @var array
     */
    public static ?Authentication $instance = null;

    /**
     * Carga al usuario autenticado
     */
    public static function loadAuthenticateUser(): void
    {
    }
}
