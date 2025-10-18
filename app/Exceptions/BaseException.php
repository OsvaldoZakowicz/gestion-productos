<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Clase base para excepciones de dominio o errores esperados.
 * Todas las excepciones personalizadas deben heredar de esta.
 */
abstract class BaseException extends Exception
{
    /**
     * Ruta a la que se redirige al usuario tras el error.
     * Puede ser sobreescrita por la subclase.
     */
    protected string $redirect_route = '/';

    /**
     * Nivel de severidad del log ('error', 'warning', 'info', etc.)
     */
    protected string $log_level = 'error';

    /**
     * Indica si debe registrarse o no este tipo de error.
     */
    protected bool $should_report = true;

    /**
     * Indica la clave que se usara para el
     * mensaje de sesion.
     */
    protected string $session_key = 'exception';

    /**
     * * Constructor.
     * permite definir mensaje de error y ruta opcional
     * de redireccion.
     */
    public function __construct(string $message = '', ?string $redirect_route = null)
    {
        // Exception es la clase padre.
        parent::__construct($message);

        if ($redirect_route) {
            $this->redirect_route = $redirect_route;
        }
    }

    /**
     * Envía el mensaje al usuario y redirige.
     */
    public function render(Request $request): RedirectResponse
    {
        return redirect()
            ->route($this->redirect_route)
            ->with($this->session_key, $this->getMessage());
    }

    /**
     * Registra el error en el log si corresponde.
     */
    public function report(): void
    {
        if ($this->should_report) {
            Log::{$this->log_level}(
                sprintf(
                    '[%s] %s en %s:%d',
                    static::class,
                    $this->getMessage(),
                    $this->getFile(),
                    $this->getLine()
                )
            );
        }
    }
}
