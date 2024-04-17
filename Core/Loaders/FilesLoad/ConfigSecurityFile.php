<?php

namespace Core\Loaders\FilesLoad;

trait ConfigSecurityFile
{
    /**
     * Default security configuration
     */
    private array $security = [
        'token_name' => 'CSRF_TOKEN',
        'token_expire' => 3600,
        'rate_limit' => [
            'enable' => true,
            'limit' => 60,
        ],
    ];

    /**
     * Obtener la configuración de seguridad por default
     */
    public function securityConfig(): array
    {
        return $this->security;
    }
}
