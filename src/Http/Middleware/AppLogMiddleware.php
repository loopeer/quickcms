<?php

namespace Loopeer\QuickCms\Http\Middleware;

use Closure;
use Loopeer\QuickCms\Models\Backend\AppLog;

class AppLogMiddleware
{

    public function handle($request, Closure $next)
    {
        $response = $next($request);
        AppLog::create(array(
            'account_id' => $request->header('account-id'),
            'url' => $request->fullUrl(),
            'route' => $request->route()->getName(),
            'action_name' => $request->route()->getActionName(),
            'method' => $request->method(),
            'build' => $request->header('build'),
            'version_name' => $request->header('version-name'),
            'platform' => $request->header('platform'),
            'device_id' => $request->header('device-id'),
            'channel_id' => $request->header('channel-id'),
            'ip' => $request->ip(),
            'content' => json_encode($request->all()),
        ));
        return $response;
    }
}