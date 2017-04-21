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

class Test extends FastModel {

    protected $buttons = [
        'actions' => [
            [
                'type' => 'confirm', 'name' => 'enabled_btn', 'text' => '启用', 'permission' => 'admin.test.change.online',
                'url' => '/admin/test/change', 'data' => ['status' => 1, 'updated_by' => 'admin', 'publish_time' => 'now'], 'where' => ['status' => [0]]
            ],
            [
                'type' => 'confirm', 'name' => 'unabled_btn', 'text' => '不启用', 'permission' => 'admin.test.change.offline',
                'url' => '/admin/test/change', 'data' => ['status' => 0], 'where' => ['status' => [0, 1]]
            ]
        ]
    ];

    protected $index = [
        ['column' => 'id', 'order' => 'desc', 'width' => '20%', 'query' => '='],
        ['column' => 'name', 'width' => '40%', 'query' => 'like'],
        ['column' => 'title', 'type' => 'limit', 'param' => '9'],
        ['column' => 'created_at', 'order' => 'asc', 'type' => 'date', 'query' => 'between'],
        ['column' => 'status', 'type' => 'select', 'param' => 'online_offline_status', 'query' => '='],
        ['column' => 'image', 'type' => 'image'],
        ['column' => 'images', 'type' => 'images'],
        ['column' => 'hidden_type', 'type' => 'normal', 'param' => ['<span class="label label-default">未启用</span>', '<span class="label label-success">已启用</span>']],
        ['column' => 'url', 'type' => 'html', 'param' => '<a href="%s" target="_blank" title="%s">点击查看</a>'],
    ];

    protected $create = [
        ['column' => 'name', 'type' => 'text', 'rules' => ['required' => true, 'email' => true]],
        ['column' => 'title', 'type' => 'password', 'rules' => ['required' => true, 'minlength' => 5]],
        ['column' => 'status', 'type' => 'select', 'param' => 'online_offline_status'],
        ['column' => 'type', 'type' => 'checkbox', 'param' => ['a', 'b', 'c']],
        ['column' => 'age', 'type' => 'radio', 'param' => [0 => '0-11', 1 => '12-30', 2 => '31-40']],
        ['column' => 'labels', 'type' => 'tags'],
        ['column' => 'start_time', 'type' => 'date'],
        ['column' => 'end_time', 'type' => 'datetime'],
        ['column' => 'pay_time', 'type' => 'time'],
        ['column' => 'content', 'type' => 'editor'],
        ['column' => 'remark', 'type' => 'editor'],
        ['column' => 'image', 'type' => 'image', 'min' => 0],
        ['column' => 'images', 'type' => 'image', 'min' => 0, 'max' => 2],
    ];
    protected $createHidden = [
        ['column' => 'created_by', 'value' => 'admin', 'action' => 'create'],
        ['column' => 'updated_by', 'value' => 'admin', 'action' => 'edit'],
        ['column' => 'hidden_type', 'value' => '1'],
    ];

    protected $detail = [
        ['column' => 'id'],
        ['column' => 'title'],
        ['column' => 'status', 'param' => 'online_offline_status'],
        ['column' => 'type'],
        ['column' => 'age'],
        ['column' => 'labels'],
        ['column' => 'id'],
        ['column' => 'content', 'type' => 'html'],
        ['column' => 'image', 'type' => 'image'],
        ['column' => 'images', 'type' => 'image'],
    ];
    protected $module = '测试';

    protected $casts = [
        'type' => 'array',
        'image' => 'qiniu',
        'images' => 'qiniu_array',
    ];
}