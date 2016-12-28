<?php

namespace Loopeer\QuickCms\Http\Middleware;

use Closure;
use Input;
use Response;

class ApiValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // signature
        $debug = Input::header('debug');
        if (is_null($debug) && config('quickcms.api_sign_validate')) {
            $sign = Input::header('sign');
            $timestamp = Input::header('timestamp');
            $params = Input::all('sign');
            // validate the timestamp
            if (($timestamp < strtotime('-10 minutes') || $timestamp > strtotime('+10 minutes'))) {
                return Response::json(array(
                    'code' => config('api.code_illegal_request'),
                    'message' => config('api.message_invalid_timestamp'),
                    'data' => NULL
                ));
            }
            // sort the params
            ksort($params);
            $str = '';
            foreach ($params as $k => $v) {
                if(is_array($v)) {
                    $tmp = implode(':', $v);
                    $str .= "$k=$tmp";
                }else {
                    $str .= "$k=$v";
                }
            }
            // validate the sign
            $validateSign = md5(rawurlencode($str . config('app.key')));
            if (strcmp($validateSign, $sign) !== 0) {
                return Response::json(array(
                    'code' => config('api.code_illegal_request'),
                    'message' => config('api.message_illegal_request'),
                    'data' => NULL
                ));
            }
        }
        return $next($request);
    }
}
