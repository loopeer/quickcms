<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/9
 * Time: 上午11:19
 */
namespace Loopeer\QuickCms\Services\Utils;

class QiniuUtil {

    /**
     * Build the qiniu image url with the specified key
     * @param $key
     * @return null|string
     */
    public static function buildQiqiuImageUrl($key) {
        if (is_null($key)) {
            return null;
        }
	\Log::info(config('quickcms.qiniu_url'));
        return config('quickcms.qiniu_url') . '/' . $key;
    }
}
