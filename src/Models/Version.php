<?php

namespace Loopeer\QuickCms\Models;

class Version extends FastModel
{
    protected $buttons = ['detail' => false];
    protected $index = [
        ['column' => 'id'],
        ['column' => 'version_code'],
        ['column' => 'url', 'type' => 'html', 'param' => '<a href="%s" target="_blank" title="%s">点击查看</a>'],
        ['column' => 'message'],
        ['column' => 'description'],
        ['column' => 'status', 'type' => 'normal', 'param' => ['<span class="label label-default">未发布</span>', '<span class="label label-default">未发布</span>']],
        ['column' => 'created_at'],
    ];
    protected $create = [
        ['column' => 'version_code', 'rules' => ['required' => true]],
        ['column' => 'url', 'rules' => ['required' => true]],
        ['column' => 'message'],
        ['column' => 'description'],
    ];

    const CREATE_STATUS = 0;
    const PUBLISH_STATUS = 1;
}
