<?php

namespace Core\Loaders;

/**
 * Realiza inyecciones de HTML a un string
 */
class HtmlInject
{
    /**
     * Constructor
     */
    public function __construct(
        private string $html
    ) {
        if (strpos($this->html, '<html') === false || strpos($this->html, '</html>') === false) {
            $this->html = "<html><head></head><body>{$this->html}</body></html>";
        }
    }

    /**
     * Inyecta html al final de <head>
     *
     * @param string $html
     * @return HtmlInject
     */
    public function headBot(string $html): HtmlInject
    {
        $this->html = preg_replace('/<\/head>/', $html . '</head>', $this->html);
        return $this;
    }

    /**
     * Inyecta html al principio de <head>
     *
     * @param string $html
     * @return HtmlInject
     */
    public function headTop(string $html): HtmlInject
    {
        $this->html = preg_replace('/<head>/', '<head>' . $html, $this->html);
        return $this;
    }

    /**
     * Inyecta al final de <body>
     *
     * @param string $html
     * @return HtmlInject
     */
    public function bodyBot(string $html): HtmlInject
    {
        $this->html = preg_replace('/<\/body>/', $html . '</body>', $this->html);
        return $this;
    }

    /**
     * Obtiene el html resultante
     */
    public function getHtml(): string
    {
        return $this->html;
    }
}
