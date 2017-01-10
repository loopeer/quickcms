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
        return config('quickCms.qiniu_url') . '/' . $key;
    }

    public static function buildQiniuUrl($key) {
        if (is_null($key)) {
            return null;
        }
        $url = config('quickCms.qiniu_url') . '/' . $key;
        if (config('quickCms.qiniu_private')) {
            $mac = new \Qiniu\Mac(config('quickCms.qiniu_access_key'), config('quickCms.qiniu_secret_key'));
            $url .= '?e=' . Carbon::now()->addMinutes(10)->timestamp;
            $url .= '&token=' . $mac->sign($url);
        }
        return $url;
    }

    public static function buildQiniuThumbnail($key) {
        if (is_null($key)) {
            return null;
        }
        $url = config('quickCms.qiniu_url') . '/' . $key . '?imageView2/2/w/200/h/100';
        if (config('quickCms.qiniu_private')) {
            $mac = new \Qiniu\Mac(config('quickCms.qiniu_access_key'), config('quickCms.qiniu_secret_key'));
            $url .= '&e=' . Carbon::now()->addMinutes(10)->timestamp;
            $url .= '&token=' . $mac->sign($url);
        }
        return $url;
    }

    public static function buildUpToken()
    {
        $bucket = config('quickCms.qiniu_bucket');
        $accessKey = config('quickCms.qiniu_access_key');
        $secretKey = config('quickCms.qiniu_secret_key');
        $policy = [
            'scope' => $bucket,
            'deadline' => time() + 604800, // 7 * 24 * 3600
        ];
        $mac = new \Qiniu\Mac($accessKey, $secretKey);
        $upToken = $mac->signWithData(json_encode($policy));
        return $upToken;
    }
}
