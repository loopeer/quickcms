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
        if ($appLog->id >= config('quickCms.app_logs_max_rows', 1000000)) {
            $this->createAppLogsTable();
        }
        return $response;
    }

    protected function createAppLogsTable()
    {
        $table = Cache::get('app_logs_table');
        $suffix = substr(strrchr($table, '_'), 1);
        $tableName = 'app_logs_' . (is_numeric($suffix) ? $suffix + 1 : 1);
        Schema::create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id')->comment('主键');
            $table->bigInteger('account_id')->default(0)->comment('用户id');
            $table->string('url', 200)->comment('路径');
            $table->string('route_name', 50)->nullable()->comment('路由名称');
            $table->string('action_name', 200)->nullable()->comment('业务名称');
            $table->string('method', 20)->nullable()->comment('请求方式');
            $table->string('build', 20)->nullable()->comment("版本号");
            $table->string('version_name', 20)->nullable()->comment("版本名称");
            $table->string('platform', 20)->nullable()->comment('平台');
            $table->string('device_id', 150)->nullable()->comment('设备');
            $table->string('channel_id', 50)->nullable()->comment('渠道');
            $table->string('ip', 20)->nullable()->comment('ip');
            $table->integer('consume_time')->nullable()->comment('耗时');
            $table->text('content')->nullable()->comment('内容');
            $table->timestamps();
            $table->softDeletes();
        });
        Cache::forever('app_logs_table', $tableName);
    }
}