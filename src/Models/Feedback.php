<?php

namespace Loopeer\QuickCms\Models;

class Feedback extends FastModel
{
    protected $buttons = ['create' => false, 'edit' => false, 'delete' => false, 'detail' => false];

    protected $index = [
        ['name' => 'ID', 'column' => 'id'],
        ['name' => '用户ID', 'column' => 'account_id'],
        ['name' => '反馈内容', 'column' => 'content', 'width' => '40%'],
        ['name' => '联系方式', 'column' => 'contact'],
        ['name' => '版本', 'column' => 'version'],
        ['name' => '设备序号', 'column' => 'device_id'],
        ['name' => '渠道', 'column' => 'channel_id'],
        ['name' => '反馈时间', 'column' => 'created_at', 'order' => 'desc'],
    ];
}
