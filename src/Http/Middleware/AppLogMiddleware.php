<?php

namespace Loopeer\QuickCms\Http\Middleware;

use Closure;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Loopeer\QuickCms\Models\Backend\AppLog;

class AppLogMiddleware
{

    public function handle($request, Closure $next)
    {
        $this->cacheAppLogsTableName();
        $beforeTime = round(microtime(true) * 1000);
        $response = $next($request);
        $appLog = AppLog::create(array(
            'account_id' => $request->header('account-id') ?: 0,
            'url' => $request->fullUrl(),
            'route_name' => $request->route()->getName(),
            'action_name' => $request->route()->getActionName(),
            'method' => $request->method(),
            'build' => $request->header('build'),
            'version_name' => $request->header('version-name'),
            'platform' => $request->header('platform'),
            'device_id' => $request->header('device-id'),
            'channel_id' => $request->header('channel-id'),
            'ip' => $request->ip(),
            'content' => json_encode($request->all()),
            'consume_time' => round(microtime(true) * 1000) - $beforeTime,
        ));
        if ($appLog->id >= config('quickCms.app_logs_max_rows', 1000000) && config('quickCms.app_logs_split', false)) {
            $this->createAppLogsTable();
        }
        return $response;
    }

    protected function createAppLogsTable()
    {
        $table = Cache::get('app_logs_table');
        $suffix = substr(strrchr($table, '_'), 1);
        $tableName = 'app_logs_' . (is_numeric($suffix) ? $suffix + 1 : 1);
        DB::statement("create table $tableName like app_logs");
        Cache::forever('app_logs_table', $tableName);
    }

    protected function cacheAppLogsTableName()
    {
        if (!Cache::has('app_logs_table') && config('quickCms.app_logs_split', false)) {
            $table = collect(DB::select('SHOW TABLES'))->map(function ($table) {
                return $table->{'Tables_in_' . config('quickCms.app_logs_database', env('DB_DATABASE'))};
            })->filter(function ($tableName) {
                return strstr($tableName, 'app_logs') !== false;
            })->sort(function ($a, $b) {
                $suffixA = substr(strrchr($a, '_'), 1);
                $suffixB = substr(strrchr($b, '_'), 1);
                $sortA = is_numeric($suffixA) ? $suffixA : 0;
                $sortB = is_numeric($suffixB) ? $suffixB : 0;
                if ($sortA == $sortB) {
                    return 0;
                }
                return ($sortA < $sortB) ? -1 : 1;
            })->last();
            Cache::forever('app_logs_table', $table);
        }
    }
}