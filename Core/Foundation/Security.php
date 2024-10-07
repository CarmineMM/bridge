<?php

namespace Core\Foundation;

use Core\Loaders\Config;

class Security
{
    /**
     * Realiza un seguimiento de seguridad en el framework
     * 
     * @param array $route Ruta actual coincidente, puede estar vacía e indicar un 404
     */
    public static function roadmap(CarryThrough $route): void
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

        // Comprobación del rate limit global
    }

    /**
     * Genera un token CSRF
     */
    public function generateCSRF_TOKEN($context, $token_name): void
    {
        $context->setStore($token_name, bin2hex(random_bytes(32)));
        $context->setStore("{$token_name}_EXPIRE", time() + Config::get('security.token_expire', 3600));
    }

    /**
     * Valida que el CSRF que incluye la petición sea correcto
     */
    public static function ValidateCsrfToken(Request $request): bool
    {
        $context = new Context;
        $token_name = Config::get('security.token_name', 'CSRF_TOKEN');
        $token = $context->getStore($token_name, null);

        if (!$token || empty($token)) {
            return false;
        }

        if ($request->getParams('{$token_name}', null) !== $token) {
            return false;
        }

        return true;
    }
}
