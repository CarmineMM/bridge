<?php

namespace Core\Foundation;

use Core\Loaders\Config;

class Security
{
    /**
     * Realiza un seguimiento de seguridad en el framework
     */
    public static function roadmap(): void
    {
        $self = new self;
        $context = new Context;
        $token_name = Config::get('security.token_name', 'CSRF_TOKEN');

        // Generar un token en caso de no existir,
        // ademas le indica el tiempo de expiración
        if (!$context->hasStore($token_name)) {
            $self->generateCSRF_TOKEN($context, $token_name);
        }

        // Regenera el token en caso de estar expirado
        if ($context->getStore("{$token_name}_EXPIRE", 0) < time()) {
            $self->generateCSRF_TOKEN($context, $token_name);
        }
    }

    /**
     * Genera un token CSRF
     */
    public function generateCSRF_TOKEN($context, $token_name): void
    {
        $context->setStore($token_name, bin2hex(random_bytes(32)));
        $context->setStore("{$token_name}_EXPIRE", time() + Config::get('security.token_expire', 3600));
    }
}
