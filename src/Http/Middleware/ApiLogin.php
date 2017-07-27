<?php

namespace Loopeer\QuickCms\Http\Middleware;

use App\Models\Api\Account;
use Closure;
use Illuminate\Support\Facades\Auth;

class ApiLogin
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
        $accountId = $request->header('account-id');
        $token = $request->header('token');
        if (!empty($accountId) && !empty($token)) {
            $accountModel = Auth::user()->getProvider()->createModel();
            $account = $accountModel::find($accountId);
        }
        if (isset($account) && $account->token == $token) {
            Auth::user()->setUser($account);
        }
        return $next($request);
    }
}
