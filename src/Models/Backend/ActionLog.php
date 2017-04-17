<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/11
 * Time: 上午11:39
 */
namespace Loopeer\QuickCms\Models\Backend;

class ActionLog extends FastModel
{
    const CREATE_TYPE = 0;
    const UPDATE_TYPE = 1;
    const DELETE_TYPE = 2;
    const LOGIN_TYPE = 3;

    protected $buttons = ['create' => false, 'edit' => false, 'detail' => true, 'delete' => false];
    protected $index = [
        ['column' => 'id'],
        ['column' => 'user_id'],
        ['column' => 'user_name'],
        ['column' => 'type', 'type' => 'select', 'param' => 'action_log_type'],
        ['column' => 'module_name'],
        ['column' => 'primary_key'],
        ['column' => 'url'],
        ['column' => 'system'],
        ['column' => 'browser'],
        ['column' => 'ip'],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $detail = [
        ['column' => 'id'],
        ['column' => 'user_id'],
        ['column' => 'user_name'],
        ['column' => 'type', 'param' => 'action_log_type'],
        ['column' => 'module_name'],
        ['column' => 'primary_key'],
        ['column' => 'url'],
        ['column' => 'system'],
        ['column' => 'browser'],
        ['column' => 'ip'],
        ['column' => 'created_at', 'order' => 'desc'],
        ['column' => 'content'],
    ];
    protected $module = '操作日志';
}
