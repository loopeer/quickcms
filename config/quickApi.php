<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: dengyongbin
 * Date: 17-01-10
 * Time: 下午2:50
 */
return [

    'api_sign_validate' => env('api_sign_validate', false),

    // API模型绑定namespace
    'model_bind' => [
        'account' => '',
        'advert' => '',
    ],

    'account' => [
        // 用户注册时是否启用IM账号注册功能
        'bind_im' => false,
        'status_usable' => 0,  //正常可用
        'status_disabled' => 1,  //暂时被禁
    ],

    // 验证码配置
    'captcha_switch' => env('captcha_switch', false),
    // 邮件验证码配置
    'mail' => [
        // 邮件验证码主题:xx captcha code
        'subject' => '',
        // 邮件验证码内容视图:views.captcha
        'view' => '',
        // 发送账号邮箱:postmaster@mg.xx.com
        'account' => '',
        // 发送账号显示名称:your app name
        'name' => '',
    ],
    // 短信验证码配置
    'sms' => [
        'captcha' => '手机验证码：%s，请于30分钟内输入使用，如非本人操作请忽略本短信。【xxx】',
        'api_key' => '',
        'api_key_verify' => '',
    ],

    // API返回码
    'code' => [
        'success' => 0,
        'failure' => -1,
        'invalid_parameters' => -2,
        'no_content' => -3,
        'precondition_failed' => -4,
    ],

    // 分页默认配置参数
    'paginate' => [
        'param_page' => 'page',
        'param_page_size' => 'page_size',
        'default_page' => 1,
        'default_page_size' => 30,
    ],

    'custom_data' => [],

];
