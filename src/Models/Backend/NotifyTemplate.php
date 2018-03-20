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

class NotifyTemplate extends FastModel {

    protected $module = '通知模板';
    protected $index = [
        ['column' => 'id', 'order' => 'desc'],
        ['column' => 'name'],
        ['column' => 'data'],
        ['column' => 'template_id'],
        ['column' => 'page'],
        ['column' => 'emphasis_keyword'],
    ];

    protected $create = [
        ['column' => 'name', 'type' => 'text', 'rules' => ['required' => true]],
        ['column' => 'data', 'type' => 'textarea', 'rules' => ['required' => true]],
        ['column' => 'template_id', 'type' => 'text', 'rules' => ['required' => true]],
        ['column' => 'page', 'type' => 'text'],
        ['column' => 'emphasis_keyword', 'type' => 'text'],
    ];

    protected $buttons = ['detail' => false,
        'actions' => [
            [
                'type' => 'confirm', 'name' => 'test_push_btn', 'text' => '测试推送', 'url' => '/admin/notifyTemplates',
                'data' => ['type' => 0],
            ],
            [
                'type' => 'confirm', 'name' => 'real_push_btn', 'text' => '全局推送', 'url' => '/admin/notifyTemplates',
                'data' => ['type' => 1],
            ],
            [
                'type' => 'dialog',
                'name' => 'custom_push_btn',
                'target' => 'custom_push',
                'method' => 'get',
                'dialog_title' => '自定义推送',
                'text' => '自定义推送',
                'url' => '/admin/notifyTemplates/customPush',
                'form' => [
                    'form_id' => 'custom-push-form',
                    'submit_id' => 'confirmPush',
                    'success_msg' => '推送成功',
                    'failure_msg' => '推送失败'
                ],
            ],
        ]
    ];
}