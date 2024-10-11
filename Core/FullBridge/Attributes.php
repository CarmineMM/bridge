<?php

namespace Core\FullBridge;

class Attributes
{
    /**
     * Etiquetas HTML que pueden contener atributos de bridge component
     */
    const ModelTags = [
        'input',
        'textarea',
        'select',

        'h1',
        'h2',
        'h3',
        'h4',
        'h5',
        'h6',

        'div',
        'article',
        'main',
        'section',
    ];

    /**
     * Propiedades que pueden contener atributos de bridge component
     */
    const ModelProperties = [
        'bridge:model'
    ];

    /**
     * Son los elementos al que se agregar el atributo value como propiedad y valor
     */
    const AddValuesToNode = [
        'input',
        'select'
    ];

    /***
     * Realiza un append en los siguientes elementos
     */
    const addHtmlContent = [
        ''
    ];
}
