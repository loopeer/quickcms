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

namespace Loopeer\QuickCms\Models;

use App\User;

class Test extends GeneralModel {

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
        ['name' => 'ID', 'column' => 'id', 'order' => 'desc', 'width' => '20%', 'query' => '='],
        ['name' => '名称', 'column' => 'name', 'width' => '40%', 'query' => 'like'],
        ['name' => '标题', 'column' => 'title', 'type' => 'limit', 'param' => '9'],
        ['name' => '创建时间', 'column' => 'created_at', 'order' => 'asc', 'type' => 'date', 'query' => 'between'],
        ['name' => '状态', 'column' => 'status', 'type' => 'select', 'param' => 'online_offline_status', 'query' => '='],
        ['name' => '单图片', 'column' => 'image', 'type' => 'image'],
        ['name' => '多图片', 'column' => 'images', 'type' => 'images'],
        ['name' => '隐藏类型', 'column' => 'hidden_type', 'type' => 'normal', 'param' => ['<span class="label label-default">未启用</span>', '<span class="label label-success">已启用</span>']],
        ['name' => '链接', 'column' => 'url', 'type' => 'html', 'param' => '<a href="%s" target="_blank" title="%s">点击查看</a>'],
        ['name' => '用户名称', 'column' => 'user.name', 'query' => 'like']
    ];

    protected $create = [
        ['name' => '名称', 'column' => 'name', 'type' => 'text', 'rules' => ['required' => true, 'email' => true]],
        ['name' => '标题', 'column' => 'title', 'type' => 'password', 'rules' => ['required' => true, 'minlength' => 5]],
        ['name' => '状态', 'column' => 'status', 'type' => 'select', 'param' => 'online_offline_status'],
        ['name' => '类型', 'column' => 'type', 'type' => 'checkbox', 'param' => ['a', 'b', 'c']],
        ['name' => '年龄', 'column' => 'age', 'type' => 'radio', 'param' => [0 => '0-11', 1 => '12-30', 2 => '31-40']],
        ['name' => '标签', 'column' => 'labels', 'type' => 'tags'],
        ['name' => '开始时间', 'column' => 'start_time', 'type' => 'date'],
        ['name' => '结束时间', 'column' => 'end_time', 'type' => 'datetime'],
        ['name' => '发布时间', 'column' => 'pay_time', 'type' => 'time'],
        ['name' => '内容', 'column' => 'content', 'type' => 'editor'],
        ['name' => '备注', 'column' => 'remark', 'type' => 'editor'],
        ['name' => '单图片', 'column' => 'image', 'type' => 'image', 'min' => 0],
        ['name' => '多图片', 'column' => 'images', 'type' => 'image', 'min' => 0, 'max' => 2],
    ];
    protected $createHidden = [
        ['column' => 'created_by', 'value' => 'admin', 'action' => 'create'],
        ['column' => 'updated_by', 'value' => 'admin', 'action' => 'edit'],
        ['column' => 'hidden_type', 'value' => '1'],
    ];

    protected $detail = [
        ['name' => 'ID', 'column' => 'id'],
        ['name' => '标题', 'column' => 'title'],
        ['name' => '状态', 'column' => 'status', 'param' => 'online_offline_status'],
        ['name' => '类型', 'column' => 'type'],
        ['name' => '年龄', 'column' => 'age'],
        ['name' => '标签', 'column' => 'labels'],
        ['name' => '开始时间', 'column' => 'id'],
        ['name' => '内容', 'column' => 'content', 'type' => 'html'],
        ['name' => '单图片', 'column' => 'image', 'type' => 'image'],
        ['name' => '多图片', 'column' => 'images', 'type' => 'image'],
        ['name' => '用户名称', 'column' => 'user.name']
    ];

    protected $casts = [
        'type' => 'array',
        'image' => 'qiniu',
        'images' => 'qiniu_array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}