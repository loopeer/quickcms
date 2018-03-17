<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 17/2/27
 * Time: 下午4:21
 */
return [
    'feedbacks' => [
        'id' => 'ID',
        'account_id' => '用户ID',
        'content' => '反馈内容',
        'contact' => '联系方式',
        'version' => '版本',
        'device_id' => '设备序号',
        'channel_id' => '渠道',
        'created_at' => '反馈时间',
    ],
    'versions' => [
        'id' => 'ID',
        'version_code' => '版本号',
        'url' => '下载地址',
        'message' => '消息提示',
        'description' => '版本描述',
        'status' => '状态',
        'created_at' => '创建时间',
        'query_form' => '版本查询',
    ],
    'systems' => [
        'id' => 'ID',
        'key' => '系统key',
        'value' => '系统value',
        'description' => '描述',
    ],
    'documents' => [
        'id' => 'ID',
        'key' => '文档key',
        'title' => '标题',
        'content' => '内容',
        'created_at' => '创建时间',
        'updated_at' => '更新时间',
    ],
    'users' => [
        'id' => 'ID',
        'name' => '名称',
        'email' => '邮箱',
        'avatar' => '头像',
        'status' => '状态',
        'password' => '密码',
        'role_id' => '所属角色',
        'last_login' => '最后登录时间',
        'created_at' => '创建时间',
        'roles' => ['display_name' => '所属角色', 'id' => '所属角色'],
    ],
    'roles' => [
        'id' => 'ID',
        'name' => '名称',
        'display_name' => '显示名称',
        'description' => '描述',
        'created_at' => '创建时间',
    ],
    'actionLogs' => [
        'id' => 'ID',
        'user_id' => '管理员ID',
        'user_name' => '管理员名称',
        'type' => '操作类型',
        'module_name' => '业务模块',
        'primary_key' => '业务ID',
        'content' => '业务内容',
        'system' => '系统',
        'browser' => '浏览器',
        'url' => '链接',
        'ip' => 'IP',
        'created_at' => '创建时间',
        'index_form' => '操作日志列表',
    ],
    'exceptionLogs' => [
        'id' => 'ID',
        'message' => '异常内容',
        'file' => '异常文件',
        'line' => '异常行',
        'code' => '异常码',
        'data' => '异常详细',
        'created_at' => '异常时间'
    ],
    'appLogs' => [
        'id' => 'ID',
        'account_id' => '用户id',
        'url' => '路径',
        'route_name' => '路由',
        'build' => '版本号',
        'version_name' => '版本名称',
        'platform' => '平台',
        'device_id' => '设备',
        'channel_id' => '渠道',
        'ip' => 'ip',
        'content' => '内容',
        'consume_time' => '耗时',
        'created_at' => '请求时间',
    ],
    'notifyTemplates' => [
        'id' => 'ID',
        'name' => '模板名称',
        'page' => '路径',
        'data' => '数据',
        'template_id' => '微信模板ID',
        'emphasis_keyword' => '放大关键词',
        'created_at' => '创建时间',
    ],
    'notifyJobs' => [
        'id' => 'ID',
        'name' => '模板名称',
        'page' => '路径',
        'data' => '数据',
        'template_id' => '微信模板ID',
        'emphasis_keyword' => '放大关键词',
        'type' => '任务类型',
        'created_at' => '创建时间',
        'push_count' => '推送数量',
        'status' => '状态',
    ],

];