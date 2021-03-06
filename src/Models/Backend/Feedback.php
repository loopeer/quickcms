<?php

namespace Loopeer\QuickCms\Models\Backend;

class Feedback extends FastModel
{
    protected $buttons = ['create' => false, 'edit' => false, 'delete' => true, 'detail' => false];

    protected $index = [
        ['column' => 'id'],
        ['column' => 'account_id'],
        ['column' => 'content', 'width' => '40%'],
        ['column' => 'contact'],
        ['column' => 'version'],
        ['column' => 'device_id'],
        ['column' => 'channel_id'],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $module = '反馈';
}
