<?php

namespace Core\Foundation;

use Core\Support\Http;

class Response
{
    /**
     * Response header
     */
    private array $headers = [
        'Content-Type' => 'text/html',
        'charset'      => 'utf-8',
    ];

    /**
     * Instancia actual
     */
    public static $instance = null;

    /**
     * Código de respuesta
     */
    private int $statusCode = 200;

    /**
     * Construye la respuesta inicial
     * 
     * @lifecycle 9: Response Make
     */
    public static function make(): Response
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $self = new self;
        $self->headers = [
            'charset'      => 'utf-8',
            'Content-Type' => Http::isAjax() ? 'application/json' : 'text/html',
        ];

        return self::$instance = $self;
    }

    /**
     * Realiza el render de la respuesta
     * 
     * @lifecycle 13: Response Send
     */
    public static function send(): void
    {
        // Set the status code
        http_response_code(self::$instance->statusCode);

        // Set the headers
        foreach (self::$instance->headers as $key => $value) {
            header("$key: $value");
        }
    }

    /**
     * Comprueba si un header es igual a un valor
     *
     * @param string $header
     * @param string $is Comparador
     * @return boolean
     */
    public static function headerIs(string $header, string $is): bool
    {
        return self::$instance->headers[$header] === $is;
    }

    /**
     * Establece el valor de un header
     */
    public function setHeader(string $header, string $value): Response
    {
        self::$instance->headers[$header] = $value;
        return $this;
    }

    /**
     * Establece el código de respuesta de la aplicación
     */
    public function setStatusCode(int $code): Response
    {
        self::$instance->statusCode = $code;
        return $this;
    }
}
