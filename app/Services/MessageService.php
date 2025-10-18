<?php

namespace App\Services;

class MessageService
{
    public const string SESSION_KEY = 'message';

    protected array $messages = [
        'resource_created' => 'Creado exitosamente.',
        'resource_updated' => 'Actualizado exitosamente.',
        'resource_deleted' => 'Eliminado exitosamente.',

        // agregar mas segun los modelos/acciones
        'default'          => 'Operacion realizada con éxito.'
    ];

    /**
     * retornar un mensaje
     * @param string $key nombre clave del mensaje
     * @return string
     */
    public function get(string $key): string
    {
        return $this->messages[$key] ?? $this->messages['default'];
    }
}