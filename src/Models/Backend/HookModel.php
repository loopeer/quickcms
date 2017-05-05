<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/1/19
 * Time: 下午5:26
 */
namespace Loopeer\QuickCms\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Loopeer\QuickCms\Services\Utils\ClientUtil;

class HookModel extends Model
{
    use SoftDeletes;
    protected $guarded = ['deleted_at'];

    public static function boot() {
        parent::boot();
        static::created(function($model)
        {
            self::saveStatistic($model, 'created');
            self::createActionLog($model, ActionLog::CREATE_TYPE);
        });

        static::updated(function($model)
        {
            self::saveStatistic($model, 'updated');
            self::createActionLog($model, ActionLog::UPDATE_TYPE);
        });

        static::deleted(function($model) {
            self::createActionLog($model, ActionLog::DELETE_TYPE);
        });
    }

    public static function createActionLog($model, $type)
    {
        if ($model instanceof ActionLog or $model instanceof Statistic or $model instanceof User or !Auth::admin()->get()) {
            return;
        }
        $client = new ClientUtil();
        ActionLog::create(array(
            'user_id' => Auth::admin()->get()->id,
            'user_name' => Auth::admin()->get()->email,
            'system' => $client->getPlatForm($_SERVER['HTTP_USER_AGENT'], true),
            'browser' => $client->getBrowser($_SERVER['HTTP_USER_AGENT'], true),
            'content' => $model instanceof User ? NULL : json_encode($model),
            'url' => request()->getRequestUri(),
            'ip' => request()->getClientIp(),
            'type' => $type,
            'primary_key' => $model->getKey(),
            'module_name' => $model->module,
        ));
    }

    public static function saveStatistic($model, $event) {
        $model_events = config('quickCms.model_events');
        foreach($model_events as $model_event) {
            if($model->getTable() == $model_event['table'] && $model_event['event'] == $event
                && (array_key_exists('where', $model_event) ? $model->{$model_event['where']['column']} == $model_event['where']['value'] : true)) {
                $statistic = Statistic::select('id', 'statistic_value')
                    ->where('statistic_key', $model_event['statistic_key'])
                    ->whereRaw("statistic_time=date_format(now(),'%Y-%m-%d')")
                    ->first();
                $statistic_value = $model_event['statistic_value'] == 1 ? 1 : $model->{$model_event['statistic_value']};
                if(!isset($statistic)) {
                    $statistic = new Statistic();
                    $statistic->statistic_value += $statistic_value;
                    $statistic->sort = $model_event['sort'];
                    $statistic->statistic_time = date('Y-m-d', time());
                    $statistic->statistic_key = $model_event['statistic_key'];
                    $statistic->save();
                } else {
                    $statistic->statistic_value += $statistic_value;
                    $statistic->save();
                }
            }
        }
    }
}
