<?php

namespace Core\Foundation;

use Core\Loaders\Config;
use Core\Support\Collection;
use Core\Support\Http;

class Request
{
    /**
     * Método del request
     *
     * @var string
     */
    public string $method = 'GET';

    /**
     * Variables del request son post
     *
     * @var array
     */
    private array|Collection $vars = [];

    /**
     * Los parámetros son de tipo GET
     */
    private ?Collection $params = null;

    /**
     * URL actual
     *
     * @var string
     */
    public string $uri = '';

    /**
     * URL actual
     *
     * @var string
     */
    public string $url = '';

    /**
     * Instancia del request
     */
    public static ?Request $instance = null;

    /**
     * User agent
     */
    public string $user_agent = '';

    /**
     * URL de la ruta coincidente
     */
    public ?Collection $route = null;

    /**
     * Determina si la petición es AJAX	
     */
    public bool $isAjax = false;

    /**
     * IP de la petición
     */
    public string $ip = '';

    /**
     * Construye el request entrante
     * 
     * @lifecycle 5: Make Request
     */
    public static function make(): Request
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        $self = new self;
        $self->uri = trim($_SERVER['REQUEST_URI'], '/');
        $self->url = trim(Config::get('app.url'), '/') . '/' . trim($_SERVER['REQUEST_URI'], '/');
        $self->method = strtoupper($_SERVER['REQUEST_METHOD']);
        $self->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $self->ip = $_SERVER['REMOTE_ADDR'];
        $self->vars = [
            'post' => new Collection($_POST, false),
            'get'  => new Collection($_GET, false),
        ];
        $self->isAjax = Http::isAjax();

        return self::$instance = $self;
    }

    /**
     * Establecer los valores de la ruta actual
     */
    public function setRoute(array $array): Request
    {
        self::$instance->route = new Collection($array, false);
        self::$instance->params = new Collection($array['params'], false);
        return $this;
    }

    /**
     * Obtener los parámetros o 1 parámetro
     */
    public function getParams(string $key = '', mixed $default = null): mixed
    {
        return $key === '' ? $this->params : $this->params->get($key, $default);
    }

    /**
     * Lleva a un arreglo el request actual
     */
    public function toArray(): array
    {
        return [
            'method' => $this->method,
            'vars' => $this->vars,
            'uri' => $this->uri,
            'url' => $this->url,
            'user_agent' => $this->user_agent,
            'route' => $this->route instanceof Collection ? $this->route->toArray() : [],
            'isAjax' => $this->isAjax,
            'ip' => $this->ip,
        ];
    }
}
