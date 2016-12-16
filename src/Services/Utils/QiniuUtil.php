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

use Carbon\Carbon;

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
        return config('quickcms.qiniu_url') . '/' . $key;
    }

    public static function buildQiniuUrl($key) {
        if (is_null($key)) {
            return null;
        }
        $url = config('quickcms.qiniu_url') . '/' . $key;
        if (config('quickcms.qiniu_private')) {
            $mac = new \Qiniu\Mac(config('quickcms.qiniu_access_key'), config('quickcms.qiniu_secret_key'));
            $url .= '?e=' . Carbon::now()->addMinutes(10)->timestamp;
            $url .= '&token=' . $mac->sign($url);
        }
        return $url;
    }

    public static function buildQiniuThumbnail($key) {
        if (is_null($key)) {
            return null;
        }
        $url = config('quickcms.qiniu_url') . '/' . $key . '?imageView2/2/w/200/h/100';
        if (config('quickcms.qiniu_private')) {
            $mac = new \Qiniu\Mac(config('quickcms.qiniu_access_key'), config('quickcms.qiniu_secret_key'));
            $url .= '&e=' . Carbon::now()->addMinutes(10)->timestamp;
            $url .= '&token=' . $mac->sign($url);
        }
        return $url;
    }
}
