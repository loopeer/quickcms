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

    //加密字符串
    'admin_pwd_salt' => '',
    'api_sign_validate' => false,
    'sql_log_switch' => true,

    // qiniu config
    'qiniu_access_key' => '',
    'qiniu_secret_key' => '',
    'qiniu_bucket' => '',
    'qiniu_url' => '',

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
    'user_status_usable'=>0,  //正常可用
    'user_status_disabled'=>1,  //暂时被禁
];
