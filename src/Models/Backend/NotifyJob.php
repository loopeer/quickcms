<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 16/4/19
 * Time: 上午9:28
 */

namespace Loopeer\QuickCms\Models\Backend;

class NotifyJob extends FastModel {

    protected $module = '通知任务';
    protected $index = [
        ['column' => 'id', 'order' => 'desc'],
        ['column' => 'name'],
        ['column' => 'data'],
        ['column' => 'template_id'],
        ['column' => 'page'],
        ['column' => 'emphasis_keyword'],
        ['column' => 'push_count'],
        ['column' => 'real_count'],
        ['column' => 'type', 'type' => 'select', 'param' => 'push_type'],
    ];

    protected $buttons = ['create' => false, 'edit' => false, 'delete' => false, 'detail' => false];
}