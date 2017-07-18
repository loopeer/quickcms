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

class AppLog extends FastModel
{
    protected $buttons = ['create' => false, 'edit' => false, 'detail' => true, 'delete' => false];
    protected $index = [
        ['column' => 'id'],
        ['column' => 'account_id', 'query' => '='],
        ['column' => 'url'],
        ['column' => 'route', 'query' => '='],
        ['column' => 'build', 'query' => '='],
        ['column' => 'version_name', 'query' => '='],
        ['column' => 'platform', 'query' => '='],
        ['column' => 'device_id'],
        ['column' => 'channel_id', 'query' => '='],
        ['column' => 'ip'],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $detail = [
        ['column' => 'id'],
        ['column' => 'account_id'],
        ['column' => 'url'],
        ['column' => 'route'],
        ['column' => 'build'],
        ['column' => 'version_name'],
        ['column' => 'platform'],
        ['column' => 'device_id'],
        ['column' => 'channel_id'],
        ['column' => 'ip'],
        ['column' => 'content'],
        ['column' => 'created_at'],
    ];
    protected $module = 'App日志';
}
