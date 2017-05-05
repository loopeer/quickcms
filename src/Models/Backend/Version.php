<?php

namespace Loopeer\QuickCms\Models\Backend;

use Carbon\Carbon;

class Version extends FastModel
{
    protected $buttons = ['detail' => false, 'delete' => true, 'actions' => [
        [
            'type' => 'confirm', 'name' => 'publish-btn', 'text' => '发布',
            'url' => '/admin/versions', 'data' => ['status' => 1, 'published_at' => 'now'], 'where' => ['status' => [0]]
        ]
    ]];
    protected $index = [
        ['column' => 'id', 'order' => 'desc'],
        ['column' => 'version_code'],
        ['column' => 'url', 'type' => 'html', 'param' => '<a href="%s" target="_blank" title="%s">点击查看</a>'],
        ['column' => 'message'],
        ['column' => 'description'],
        ['column' => 'status', 'type' => 'normal', 'param' => ['<span class="label label-default">未发布</span>', '<span class="label label-success">已发布</span>']],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $create = [
        ['column' => 'version_code', 'rules' => ['required' => true]],
        ['column' => 'url', 'rules' => ['required' => true]],
        ['column' => 'message'],
        ['column' => 'description'],
    ];
    protected $module = '版本';

    const CREATE_STATUS = 0;
    const PUBLISH_STATUS = 1;

    public function setPublishedAtAttribute()
    {
        if (empty($this->attributes['published_at'] || $this->attributes['published_at'] == 'now')) {
            $this->attributes['published_at'] = Carbon::now();
        }
    }
}
