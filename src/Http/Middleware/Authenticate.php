<?php

namespace Loopeer\QuickCms\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Loopeer\QuickCms\Http\Controllers\Api\ApiResponse;

class Authenticate
{

    const NORMAL_STATUS = 0;

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $accountId = $request->header('account-id');
        $token = $request->header('token');
        $validator = Validator::make(['account-id' => $accountId, 'token' => $token], [
            "account-id" => "required",
            "token" => "required"
        ]);
        if ($validator->passes()) {
            $account = Auth::user()->get();
            if (isset($account) && $account->status == static::NORMAL_STATUS) {
                return $next($request);
            }
        }
        return ApiResponse::errorUnauthorized();
    }
}
