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
    'site_title' => '后台管理系统',

    //加密字符串
    'admin_pwd_salt' => '',
    'api_sign_validate' => false,
    'sql_log_switch' => true,
    'permission_switch' => true,

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


    // sms config
    'sms_api_switch' => false,
    'sms_captcha' => '手机验证码：%s，请于30分钟内输入使用，如非本人操作请忽略本短信。【loopeer】',
    'sms_api_key' => '',

    // quick api model namespace
    'account_model_class' => '',
    'push_model_class' => '',
    'feedback_model_class' => '',
    'system_model_class' => '',

    // api response message and code
    'code_success' => 0,
    'message_success' => '请求成功',
    'message_update_success' => '修改成功',
    'code_invalid_timestamp' => 1,
    'message_invalid_timestamp' => '非法请求时间戳',
    'code_illegal_request' => 2,
    'message_illegal_request' => '非法请求',
    'code_default_error' => 3,
    'message_default_error' => '请求失败',
    'code_invalid_parameters' => 4,
    'message_invalid_parameters' => '不合法的请求参数',
    'code_invalid_pagination_parameters' => 5,
    'message_invalid_pagination_parameters' => '不合法的分页参数',
    'code_no_content' => 6,
    'message_content_not_exist' => '请求的内容不存在',
    'code_no_version_upgrade' => 16,
    'message_no_version_upgrade' => '已是最新版本',
    'message_account_not_exist' => '账号不存在',
    'message_password_error' => '密码错误',
    'message_phone_is_register' => '该手机号已被注册',
    'message_captcha_error' => '验证码输入错误',
    'message_oldPassword_error' => '旧密码输入错误',
    'code_black_account' => 20,
    'message_black_account' => '账号已禁用',
    'code_precondition_failed' => 412,
    'REQUEST_PARAMETER_USER_ID' => 'account_id',
    'REQUEST_PARAMETER_TOKEN' => 'token',
    'REQUEST_PARAMETER_PAGE' => 'page',
    'REQUEST_PARAMETER_PAGE_SIZE' => 'page_size',
    'paginator_default_page' => 1,
    'paginator_default_page_size' => 30,
    'DEFAULT_AD_COUNT' => 1,
    'DEFAULT_POST_LIMIT' => 1,

    //account_status
    'user_status_usable' => 0,  //正常可用
    'user_status_disabled' => 1,  //暂时被禁

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

    'custom_data' => [],
];
