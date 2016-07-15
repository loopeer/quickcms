<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/7/15
 * Time: 下午5:36
 */
namespace Loopeer\QuickCms\Services\Utils;

use Illuminate\Support\Facades\Cache;

class ApiUtil {

    /**
     * 验证码是否一致
     * @param string $phone 电话号码
     * @param string $captcha 验证码
     * @return bool
     */
    public function checkCaptcha($phone, $captcha) {
        $captcha_service = Cache::get($phone);
        if ($captcha != $captcha_service) {
            return true;
        }
        return false;
    }
}