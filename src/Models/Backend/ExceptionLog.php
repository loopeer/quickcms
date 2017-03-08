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

class ExceptionLog extends FastModel
{
    protected $buttons = ['create' => false, 'edit' => false, 'detail' => true, 'delete' => false];
    protected $index = [
        ['column' => 'id'],
        ['column' => 'message'],
        ['column' => 'code'],
        ['column' => 'file'],
        ['column' => 'line'],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $detail = [
        ['column' => 'id'],
        ['column' => 'message'],
        ['column' => 'code'],
        ['column' => 'file'],
        ['column' => 'line'],
        ['column' => 'data'],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $module = '异常日志';
}
