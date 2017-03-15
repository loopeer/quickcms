<?php

namespace Loopeer\QuickCms\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $path = $request->path();
        $exception = FlattenException::create($e);
        $statusCode = $exception->getStatusCode($exception);
        //API
        if (strstr($path, 'api/') !== false) {
            $response = [
                'code' => $statusCode,
                'message' => trans('messages.default_error'),
                'data' => null
            ];
            if (config('app.debug')) {
                $response['debug'] = [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'class' => get_class($e),
                    'trace' => explode("\n", $e->getTraceAsString()),
                ];
            }
            return response()->json($response, $statusCode);
        }
        //Backend
        if(view()->exists('backend::errors.error') && !config('app.debug')) {
            return response()->view('backend::errors.error', ['code' => $statusCode], $statusCode);
        }

        return parent::render($request, $e);
    }
}
