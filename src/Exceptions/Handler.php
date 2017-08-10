<?php

namespace Loopeer\QuickCms\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Loopeer\QuickCms\Models\Backend\ExceptionLog;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        NotFoundHttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::clsss,
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
        ExceptionLog::create(array(
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'code' => $e->getCode(),
            'line' => $e->getLine(),
            'data' => $e,
        ));
        $monolog = Log::getMonolog();
        $monolog->pushHandler(new BearyChatHandler());

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
