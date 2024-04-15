<?php

namespace Core\Translate;

use Core\Foundation\Filesystem;
use Core\Loaders\Config;

class Translate
{
    /**
     * Idioma interno para mensajes y errores del framework
     */
    const InternalLocales = ['en', 'es'];

    /**
     * Archivos internos para los idiomas
     */
    private string $internalLocaleFiles = 'Core/Translate/locale';

    /**
     * Fallback para la localización de idiomas interno
     */
    const FallbackLocale = 'en';

    /**
     * Cargar los archivos de localización
     *
     * @lifecycle 6: Load Translate files
     */
    public static function make(): void
    {
        $self = new self;
        $self->loadInternalFiles();
        $self->loadFrameworkFiles();
    }

    /**
     * Cargar los archivos de traducción del framework,
     * usados por el usuario en la construcción de la aplicación
     * 
     * @lifecycle 6.2: Load Translate files (framework)
     */
    public function loadFrameworkFiles(string $files_folder = '', string $namespace = ''): void
    {
        $folder = $files_folder ? $files_folder : Filesystem::rootPath(
            Filesystem::explodeStringPath(Config::get('app.locale_folder', ''))
        );

        if (!is_dir($folder)) {
            throw new \Exception("The folder '{$folder}' does not exist.");
            return;
        }

        foreach (Filesystem::scanFolder($folder) as $key => $value) {
            if ($key === 'files') {
                foreach ($value as $file) {
                    $lang = pathinfo($file, PATHINFO_FILENAME);
                    Lang::_set(
                        translates: $this->applyNamespace(
                            json_decode(file_get_contents($folder . DIRECTORY_SEPARATOR . $file), true) ?? [],
                            $namespace
                        ),
                        lang: $lang,
                        is: 'framework'
                    );
                }
            } else {
                foreach ($value as $innerFolder) {
                    $this->loadFrameworkFiles(
                        Filesystem::constructPath([$folder, $innerFolder]),
                        $innerFolder
                    );
                }
            }
        }
    }

    /**
     * Aplica namespace sobre archivos
     */
    public function applyNamespace(array $lang, string $namespace = ''): array
    {
        if (!$namespace) {
            return $lang;
        }

        $newLang = [];

        foreach ($lang as $key => $value) {
            $newLang[$namespace . ':' . $key] = $value;
        }

        return $newLang;
    }

    /**
     * Carga los archivos internos de traducción
     * 
     * @lifecycle 6.1: Load Translate files (internal)
     */
    private function loadInternalFiles(): Translate
    {
        foreach (self::InternalLocales as $lang) {
            Lang::_set(
                translates: $this->loadFile($lang),
                lang: $lang,
                is: 'internal'
            );
        }

        return $this;
    }

    /**
     * Carga el archivo y lo retorna
     */
    private function loadFile(string $name): array
    {
        $file = $this->internalLocaleFiles . DIRECTORY_SEPARATOR . $name . '.json';

        if (file_exists($file)) {
            return json_decode(file_get_contents($file), true) ?? [];
        }

        return [];
    }

    /**
     * Reemplaza argumentos en un texto
     *
     * @param string $text
     * @param array $args
     * @return string
     */
    public static function replacementArgs(string $text, $args = []): string
    {
        foreach ($args as $key => $value) {
            $text = str_replace("{{$key}}", $value, $text);
        }

        return $text;
    }
}
