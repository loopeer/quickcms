<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/22
 * Time: 下午6:03
 */
namespace Loopeer\QuickCms\Models\Api;

class System extends ApiBaseModel {

    protected static $systems;

    public static function getValue($key)
    {
        if (!self::$systems) {
            self::$systems = self::all()->pluck('value', 'key');
        }

        return self::$systems[$key];
    }
}
