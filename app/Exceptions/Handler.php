<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        'new_password',
        'comfirm_new_password',
        'passwordV',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Deux inscriptions simultanées : la base refuse la seconde, on l'explique proprement.
        $this->renderable(function (\Illuminate\Database\QueryException $e, $request) {
            if (preg_match('/users[._](email|matricule)_cle/', $e->getMessage(), $m) && (string) $e->getCode() === '23000') {
                return redirect()->back()->withInput($request->except($this->dontFlash))->withErrors([
                    $m[1] => $m[1] === 'email' ? 'Un compte utilise déjà cette adresse email.' : 'Un compte utilise déjà ce numéro matricule.',
                ]);
            }
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/login/admin');
        }
        return redirect()->guest(route('login'));
    }

    

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        return parent::render($request, $exception);
    }

}
