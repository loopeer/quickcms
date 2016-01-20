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
namespace Loopeer\QuickCms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HookModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $hidden = array('created_at', 'updated_at', 'deleted_at');
    protected $guarded = array('created_at', 'updated_at', 'deleted_at');

    public static function boot() {
        parent::boot();
        static::created(function($model)
        {
            self::saveStatistic($model, 'created');
        });

        static::updated(function($model)
        {
            self::saveStatistic($model, 'updated');
        });
    }

    public static function saveStatistic($model, $event) {
        $model_events = config('quickcms.model_events');
        foreach($model_events as $model_event) {
            if($model->getTable() == $model_event['table'] && $model_event['event'] == $event
                && (array_key_exists('where', $model_event) ? $model->$model_event['where']['column'] == $model_event['where']['value'] : true)) {
                $statistic = Statistic::select('id', 'statistic_value')
                    ->where('statistic_key', $model_event['statistic_key'])
                    ->whereRaw("statistic_time=date_format(now(),'%Y-%m-%d')")
                    ->first();
                $statistic_value = $model_event['statistic_value'] == 1 ? 1 : $model->$model_event['statistic_value'];
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