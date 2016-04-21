<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/15
 * Time: 下午6:20
 */
return [
    //登录日志管理
    'model_class' => 'Loopeer\QuickCms\Models\ActionLog',
    'model_name' => '操作日志',
    'index_multi' => true,
    'index_multi_join' => array(
        ['users', 'action_logs.user_id', '=', 'users.id'],
        ['users as users2', 'action_logs.user_id', '=', 'users2.id'],
    ),
    'index_multi_column' => array('action_logs.id', 'users2.name', 'users.email', 'action_logs.client_ip', 'action_logs.created_at'),
    'index_column' => array('id', 'name', 'email', 'client_ip', 'created_at'),
    'index_column_name' => array('ID', '姓名', '邮箱', 'IP', '时间'),
];