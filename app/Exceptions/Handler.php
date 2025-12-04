<?php

namespace App\Exceptions;

// use Exception;
Use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Sobrescribir el funcionamiento por defecto (\Illuminate\Foundation\Exceptions\Handler.php)
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface   $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface  $e)
    {
        // gestión de la expiración del token de formularios
        // https://gist.github.com/jrmadsen67/bd0f9ad0ef1ed6bb594e
        // se vuelve al formulario con un mensaje de aviso.
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            // Alerta de recarga de página
            session()->flash('alerta_flash', 'Por seguridad, la página ha caducado. Debe enviar el formulario de nuevo.');
            return redirect()
                    ->back()
                    ->withInput($request->except('password'));
        }

        // gestión de errores: página de error por defecto (solo para modo PRODUCTION)
        if (!env('APP_DEBUG', false) && !view()->exists("errors.{$e->getStatusCode()}")) {
            return response()->view('errors.default', ['exception' => $e], 500, $e->getHeaders());
        }

        return parent::renderHttpException($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
