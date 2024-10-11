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
}
