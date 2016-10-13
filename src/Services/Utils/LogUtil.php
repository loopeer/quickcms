<?php

/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/9
 * Time: 下午5:43
 */
namespace Loopeer\QuickCms\Services\Utils;

use Monolog\Handler\FirePHPHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogUtil {

    /**
     * 获取业务日志对象
     * @param $name
     * @param $dir
     * @return Logger
     */
    public static function getLogger($name, $dir = null, $type = 'daily') {
        $logger = new Logger($name);
        $date = date('Ymd', time());
        $file_name = $name . '_' . $date . '.log';
        if ($type == 'single') {
            $file_name = $name.'.log';
        }
        $path = storage_path() . '/logs/' . ($dir ? ($dir . '/') : '') . $file_name;
        $stream = new StreamHandler($path, Logger::INFO, true, 0664);
        $fire_php = new FirePHPHandler();
        $logger->pushHandler($stream);
        $logger->pushHandler($fire_php);
        return $logger;
    }
}
