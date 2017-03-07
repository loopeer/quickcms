<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 17/3/7
 * Time: 下午2:14
 */
namespace Loopeer\QuickCms\Models\Backend;

trait ActionLogEvent {

    public static function boot() {
        \Log::info('action log event boot');
        parent::boot();
        static::created(function($model)
        {
            \Log::info('action log event created...');
        });

        static::updated(function($model)
        {
            \Log::info('action log event updated...');
        });
    }
}