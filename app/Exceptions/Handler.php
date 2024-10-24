<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

// 追加
//use Illuminate\session\TolenMismatchException;
//use Illuminate\Auth\AuthenticationException;

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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */

    //public function render($request, Throwable $exception)
    public function render($request, Exception $exception)
    {
         //追加(タイムアウトエラー)
         /*if ($exception instanceof TokenMismatchException) {
			return redirect()->route('login');
		}*/
        /*if ($e instanceof Illuminate\Session\TokenMismatchException) {
        if (url()->current() == route('logout')) {
            return redirect()->route('login');
            }
        }
        return parent::render($request, $e);*/

        return parent::render($request, $exception);
    }

    /*public function unauthenticated($request, AuthenticationException $exception)
   {
       if (in_array('guest', $exception->guards())) {
           return redirect()->guest(route('loginView'));
       }
    }*/
}
