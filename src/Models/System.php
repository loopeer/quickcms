<?php

namespace Loopeer\QuickCms\Models;

class System extends FastModel
{
    protected $buttons = ['detail' => false];
    protected $index = [
        ['column' => 'id'],
        ['column' => 'key'],
        ['column' => 'value'],
        ['column' => 'description'],
    ];

    protected $create = [
        ['column' => 'key', 'rules' => ['required' => true]],
        ['column' => 'value', 'rules' => ['required' => true]],
        ['column' => 'description', 'rules' => ['required' => true]],
    ];
}
