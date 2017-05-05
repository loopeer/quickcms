<?php

namespace Loopeer\QuickCms\Models\Backend;

class System extends FastModel
{
    protected $buttons = ['detail' => false, 'customs' => [
        ['route' => '/admin/systems/setting', 'name' => '基本设置']
    ]];
    protected $index = [
        ['column' => 'id', 'order' => 'desc'],
        ['column' => 'key'],
        ['column' => 'value'],
        ['column' => 'description'],
    ];

    protected $create = [
        ['column' => 'key', 'rules' => ['required' => true, 'specificString' => true, 'english' => true]],
        ['column' => 'value', 'rules' => ['required' => true]],
        ['column' => 'description', 'rules' => ['required' => true]],
    ];
    protected $module = '系统设置';
}
