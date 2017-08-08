<?php

namespace Loopeer\QuickCms\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Loopeer\QuickCms\Models\Backend\ExceptionLog;
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
        ExceptionLog::create(array(
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'code' => $e->getCode(),
            'line' => $e->getLine(),
            'data' => $e,
        ));
        $payload = ['text' => 'app_name: ' . env('DB_DATABASE') . ' ------ app_env: ' . env('APP_ENV')
            . ' ------- error_code: ' . $e->getCode() . ' -------- error_line: ' . $e->getLine(),
            'channel' => 'Server-Log-Report',
            'attachments' => [['title' => $e->getMessage(), 'text' => $e->getTraceAsString(), 'color' => '#ffa500']]];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('quickApi.log_report_hook'));
        curl_setopt($ch, CURLOPT_ENCODING , "UTF-8");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['payload' => json_encode($payload)]);
        curl_exec($ch);
        curl_close($ch);

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
