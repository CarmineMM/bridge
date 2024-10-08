<?php

namespace Core\Foundation\RateLimit\Driver;

use Core\Foundation\RateLimit\Base\RateLimitBaseDriver;
use Core\Foundation\Request;
use Core\Implements\RateLimitDriver;
use Dotenv\Util\Regex;

class Session extends RateLimitBaseDriver implements RateLimitDriver
{
    /**
     * Key para acceder al session controller
     */
    public static string $key = 'bridge_sessions';

    /**
     * Crea el registro por primera vez en caso de no existir.
     * En otras palabras para usuarios nuevos
     */
    private function create(Request $request): void
    {
        $_SESSION[static::$key][$request->ip] = [
            'count'      => 0,
            'time'       => time() + $this->timeRoadmap,
            'ip'         => $request->ip,
            'user_id'    => 0,
            'user_agent' => $request->user_agent,
            'ban_until'  => false
        ];
    }

    /**
     * Verifica las peticiones, dispara una exception si se ha superado el limite
     */
    public function check(Request $request): void
    {
        if (!isset($_SESSION[static::$key][$request->ip])) {
            $this->create($request);
            return;
        }

        $this->reset($request);

        if ($_SESSION[static::$key][$request->ip]['count'] >= $this->limit) {
            $this->banned();
        }

        // Si se ha superado el limite disparar un error
        if ($_SESSION[static::$key][$request->ip]['ban_until'] > time()) {
            throw new \Exception("Rate limit exceeded", 429);
        }
    }

    /**
     * Incrementa el limite de peticiones
     */
    public function increment(): void
    {
        $request = Request::make();
        $_SESSION[static::$key][$request->ip]['count'] = $_SESSION[static::$key][$request->ip]['count'] + 1;
    }

    /**
     * El reset verifica si ha pasado el tiempo de espera y reset el contador
     */
    public function reset(Request $request): void
    {
        if (
            (time() > $_SESSION[static::$key][$request->ip]['time'] && $_SESSION[static::$key][$request->ip]['ban_until'] === false) ||
            (time() > $_SESSION[static::$key][$request->ip]['ban_until'] && $_SESSION[static::$key][$request->ip]['ban_until'] !== false)
        ) {
            unset($_SESSION[static::$key][$request->ip]);
            $this->create($request);
        }
    }

    /**
     * Ban al usuario por el tiempo
     *
     * @return void
     */
    public function banned(): void
    {
        $request = Request::make();
        $_SESSION[static::$key][$request->ip]['ban_until'] = time() + $this->banTime;
    }

    /**
     * Lista de ban times
     */
    public static function list(): array
    {
        return $_SESSION[Session::$key] ?? [];
    }

    /**
     * Reset toda la lista,
     * En caso del session no funcionara, porque las sesiones son independiente por navegador
     * y por tanto no se restablecen, sin embargo puede incurrir la acción desde el cliente.
     */
    public static function resetAllKeys(): void
    {
        $_SESSION[Session::$key] = [];
    }
}
