<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-11-6
 * Time: 下午7:14
 */
return [
    //操作日志
    'action_log' => array(
        'login' => '登陆系统'
    ),
    'site_title' => env('site_title', '后台管理系统'),

    //加密字符串
    'admin_pwd_salt' => '',
    'sql_log_switch' => env('sql_log_switch', false),
    'permission_switch' => env('permission_switch', false),

    // qiniu config
    'qiniu_access_key' => env('qiniu_access_key'),
    'qiniu_secret_key' => env('qiniu_secret_key'),
    'qiniu_bucket' => env('qiniu_bucket'),
    'qiniu_url' => env('qiniu_url'),

    // baidu push config
    'baidu_push_android_api_key' => env('baidu_push_android_api_key'),
    'baidu_push_android_secret_key' => env('baidu_push_android_secret_key'),
    'baidu_push_ios_api_key' => env('baidu_push_ios_api_key'),
    'baidu_push_ios_secret_key' => env('baidu_push_ios_secret_key'),
    'baidu_push_sdk_deploy_status' => env('baidu_push_sdk_deploy_status'),

    // admin layout
    'admin_body_layout' => 'menu-on-top',

    // statistic
    'statistic_key' => array('用户数', '产品数', '评论数', '问题数'),
    // model_event_hook
    'model_events' => array(
        array(
            'table' => 'versions',
            'event' => 'created',
            'statistic_key' => '版本数',
            'sort' => 13,
            'statistic_value' => 'version'
        ),
        array(
            'table' => 'versions',
            'event' => 'updated',
            'statistic_key' => '上线版本数',
            'sort' => 13,
            'statistic_value' => 1,
            'where' => array('column' => 'status', 'value' => 1)
        ),
        array(
            'table' => 'feedbacks',
            'event' => 'created',
            'statistic_key' => '反馈数',
            'sort' => 14,
            'statistic_value' => 1
        ),
    ),

    // email config
    'sendcloud_api_key' => env('SENDCLOUD_API_KEY'),
    'sendcloud_api_users' => explode(',', env('SENDCLOUD_API_USER')),

    'hide_tips_time' => 3000,

    //登录失效时间，单位分钟
    'login_lifetime' => 60,
];
