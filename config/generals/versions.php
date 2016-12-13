<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/15
 * Time: 下午6:20
 */
return [
    //版本管理
    'middleware' => array('admin.versions'),
    'model_class' => 'Loopeer\QuickCms\Models\Version',
    'model_name' => '版本',
    'index_column' => array('id', 'platform', 'version_code', 'version', 'url', 'message', 'description', 'status', 'published_at'),
    'index_column_name' => array('ID', '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '版本状态', '发布时间'),
    'edit_column' => array('platform', 'version_code', 'version', 'url', 'description', 'published_at'),
    'edit_column_name' => array('发布平台', '版本号', '版本名称', '下载地址', '版本描述', '发布时间'),
    'index_column_rename' => array(
        'status' => array(
            'type' => 'normal',
            'param' => array(
                0 => '<span class="label label-default">未上线</span>',
                1 => '<span class="label label-success">已上线</span>',
            ),
        ),
        // 字段名配置
        'version_code' => array(
            //点击数字出现模态框
            'type' => 'dialog',
            'param' => array(
                // a标签name名称
                'name' => 'test_btn',
                //模态框名称
                'target' => 'test_dialog',
                //模态框标题
                'dialog_title' => 'Modal',
                //模态框路由，结尾的'/'不能省略，url后会传递id值，路由需配置
                'url' => '/admin/test/detail/',
                'width' => '80%'
            )
        )
    ),
    // 下拉按钮配置
    'table_action' => array(
        array(
            'type' => 'confirm',
            'name' => 'online_btn',
            'display_name' => '上线',
            'url' => '/admin/versions/changeStatus',
            'data' => array('status' => 1),
            'where' => array('status' => [0]),
            'confirm_msg' => '确定要上线吗?',
        ),
        array(
            'type' => 'confirm',
            'name' => 'offline_btn',
            'display_name' => '下线',
            'url' => '/admin/versions/changeStatus',
            'data' => array('status' => 0),
            'where' => array('status' => [1]),
            'confirm_msg' => '确定要下线吗?',
        ),
    ),
    'edit_column_detail' => array(
        'published_at' => array(
            //type 暂时只支持 date/time/selector，selector 需要配置 key 值，例：'type'=>'selector:key'
            'type' => 'date',
            //验证暂时只支持 true 或 false 类型
            'validator' => array('required' => true),
            //message 为选填
            'message' => array('required' => '必需填写时间发布时间')
        )
    ),
    'edit_editor' => true,
];