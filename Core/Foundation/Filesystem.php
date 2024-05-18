<?php

namespace Core\Foundation;

class Filesystem
{
    /**
     * Construct un path a partir de un arreglo
     *
     * @param array $array
     * @return string
     */
    public static function constructPath(array $array): string
    {
        return implode(
            DIRECTORY_SEPARATOR,
            array_map(fn ($item) => trim($item, '/\\'), $array)
        );
    }

    /**
     * Construir a la ruta raíz del framework
     */
    public static function rootPath(array $construct = []): string
    {
        return ROOT_PATH . DIRECTORY_SEPARATOR . self::constructPath($construct);
    }

    /**
     * Path hacia la carpeta 'app'
     */
    public static function appPath(array $construct = []): string
    {
        return ROOT_PATH . DIRECTORY_SEPARATOR . self::constructPath(['app', ...$construct]);
    }

    /**
     * Path hacia la carpeta 'public'
     */
    public static function publicPath(array $construct = []): string
    {
        return PUBLIC_PATH . DIRECTORY_SEPARATOR . self::constructPath($construct);
    }

    /**
     * Realizar un explode sobre un string, ya sea que venga en dot notation o en slash notation.
     * Sin embargo, hace un exclude sobre ciertas extensiones, para evitar explode sobre los mismos archivos.
     * Por ejemplo: 'app.resources.views.home.php' terminara como: ['app', 'resources', 'views', 'home.php']
     */
    public static function explodeStringPath(string $string): array
    {
        $excludedExtensions = ['txt', 'php', 'js', 'css', 'view.php'];

        $regex = '/[\.\/](?!' . implode('|', $excludedExtensions) . '$)/';

        return preg_split($regex, $string);
    }

    /**
     * Escanea los archivos de una carpeta, y los clasifica por archivos y carpetas
     */
    public static function scanFolder(string $path): array
    {
        $files = [];
        $folders = [];

        foreach (scandir($path) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            if (is_dir($path . DIRECTORY_SEPARATOR . $file)) {
                $folders[] = $file;
            } else {
                $files[] = $file;
            }
        }

        return [
            'files' => $files,
            'folders' => $folders
        ];
    }
}
