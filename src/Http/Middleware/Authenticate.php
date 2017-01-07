<?php

namespace Loopeer\QuickCms\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Validator;
use Input;
use Loopeer\QuickCms\Http\Controllers\Api\ApiResponse;

class Authenticate
{
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
        $accountId = Input::get('account_id');
        $token = Input::get('token');
        $validator = Validator::make(['account_id'=>$accountId, 'token'=>$token], [
            "account_id" => "required",
            "token" => "required"
        ]);
        if ($validator->passes()) {
            $accountModel = Auth::user()->getProvider()->createModel();
            $account = $accountModel->whereId($accountId)->whereToken($token)->where('status', 0)->first();
            if (!is_null($account)) {
                Auth::user()->setUser($account);
                return $next($request);
            }
        }
        return ApiResponse::errorUnauthorized();
    }
}
