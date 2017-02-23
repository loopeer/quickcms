<?php

namespace Loopeer\QuickCms\Models;

class Version extends FastModel
{
    protected $buttons = ['detail' => false];
    protected $index = [
        ['name' => 'ID', 'column' => 'id'],
        ['name' => '版本号', 'column' => 'version_code'],
        ['name' => '下载地址', 'column' => 'url', 'type' => 'html', 'param' => '<a href="%s" target="_blank" title="%s">点击查看</a>'],
        ['name' => '消息提示', 'column' => 'message'],
        ['name' => '版本描述', 'column' => 'description'],
        ['name' => '状态', 'column' => 'status', 'type' => 'normal', 'param' => ['<span class="label label-default">未发布</span>', '<span class="label label-default">未发布</span>']],
        ['name' => '创建时间', 'column' => 'created_at'],
    ];
    protected $create = [
        ['name' => '版本号', 'column' => 'version_code', 'rules' => ['required' => true]],
        ['name' => '下载地址', 'column' => 'url', 'rules' => ['required' => true]],
        ['name' => '消息提示', 'column' => 'message'],
        ['name' => '版本描述', 'column' => 'description'],
    ];

    const CREATE_STATUS = 0;
    const PUBLISH_STATUS = 1;
}
