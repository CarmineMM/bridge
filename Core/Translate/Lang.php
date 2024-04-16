<?php

namespace Core\Translate;

use Core\Loaders\Config;
use Core\Support\Collection;

/**
 * Almacena y obtiene los archivos de idioma
 */
class Lang
{
    /**
     * Listado de traducciones de idiomas
     */
    public array $translations = [];

    /**
     * Listado interno de traducciones de idiomas
     *
     * @var array
     */
    private array $internalTranslations = [];

    /**
     * Listado de traducciones de idiomas fallback
     */
    private string $fallback = 'en';

    /**
     * Instance
     */
    public static $instance = null;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->fallback = Config::get('app.fallback_locale', 'en');
    }

    /**
     * Instance singleton
     */
    public static function __getInstance(): Lang
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->fallback = Config::get('app.fallback_locale', 'en');
        }

        return self::$instance;
    }

    /**
     * Establece los archivos de idiomas
     *
     * @param array $translates
     * @return void
     */
    public static function _set(array $translates, string $lang, string $is = 'internal'): void
    {
        $newInstance = static::__getInstance();

        match ($is) {
            'framework' => $newInstance->internalTranslations[$lang] = new Collection($translates, false),
            default => $newInstance->translations[$lang] = new Collection($translates, false),
        };

        self::$instance = $newInstance;
    }

    /**
     * Traduce un texto
     *
     * @param string $key Key del texto
     * @param array $args Variables a reemplazar
     * @param string $lang Idioma (Asignado manualmente)
     * @return string Si no encuentra la traducción devuelve el key
     */
    public static function get(string $key, array $args = [], string $lang = ''): string
    {
        $lang = static::__getInstance();

        if ($lang === '') {
            $lang = Config::get('app.locale', 'en');
        }

        return Translate::replacementArgs(
            $lang->translations[$lang]->get(
                $key,
                $lang->translations[$lang->fallback]->get($key, $key)
            ),
            $args
        );
    }

    /**
     * Obtiene traducciones internas
     */
    public static function _get(string $key, array $args = [], string $default = ''): string
    {
        $lang = static::__getInstance();
        $appLang = Config::get('app.locale', 'en');

        if (in_array($appLang, Translate::InternalLocales)) {
            return Translate::replacementArgs(
                $lang->internalTranslations[$appLang]->get($key, $default),
                $args
            );
        }

        return Translate::replacementArgs(
            $lang->internalTranslations[Translate::FallbackLocale]->get($key, $default),
            $args
        );
    }
}
