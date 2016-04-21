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
    //用户管理，仅列表页
    'middleware' => array(
        'admin.users'
    ),
    'model_class' => 'Loopeer\QuickCms\Models\User',
    'model_name' => '用户',
    'index_select_column' => array(
        'users.id', 'users.name', 'users.email', 'created_at', 'last_login'
    ),
    'index_multi' => true,
    'index_multi_join' => array(
        ['role_user', 'users.id', '=', 'role_user.user_id'],
        ['roles', 'roles.id', '=', 'role_user.role_id'],
    ),
    'index_multi_column' => array(
        'users.id as user_id', 'users.name as user_name', 'users.email as email', 'users.created_at as user_created_at',
        'users.last_login as last_login', 'users.status as status', 'roles.display_name as role_name'
    ),
    'index_column' => array(
        'user_id', 'user_name', 'email', 'role_name', 'user_created_at', 'last_login', 'status'
    ),
    'index_column_name' => array(
        'ID', '姓名', '邮箱', '所属角色', '创建时间', '最后登录时间', '状态', '操作'
    ),
    'index_column_rename' => array(
        // 字段名配置
        'status' => array(
            //正常状态显示替换
            'type' => 'normal',
            'param' => array(
                //数据库值为键名，显示内容为键值
                0 => '<span class="label label-default">已禁用</span>',
                1 => '<span class="label label-success">已启用</span>',
            ),
        ),
    ),
    // 下拉按钮配置
    'table_action' => array(
        array(
            'default_show' => true,
            'type' => 'dialog',
            'name' => 'set_role',
            'target' => 'set_role',
            'dialog_title' => '分配角色',
            'display_name' => '分配角色',
            'url' => '/admin/users/role/',
            'form' => array(
                'form_id' => 'smart-form-register',
                'submit_id' => 'submitRole',
                'success_msg' => '分配成功',
                'failure_msg' => '分配失败'
            ),
        ),
        array(
            'type' => 'confirm',
            'name' => 'enabled_btn',
            'display_name' => '启用',
            'url' => '/admin/users/changeStatus',
            'data' => array('status' => 1),
            'where' => array('status' => [0]),
            'confirm_msg' => '确定要启用吗?',
        ),
        array(
            'type' => 'confirm',
            'name' => 'disabled_btn',
            'display_name' => '禁用',
            'url' => '/admin/users/changeStatus',
            'data' => array('status' => 0),
            'where' => array('status' => [1]),
            'confirm_msg' => '确定要禁用吗?',
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
        ),
        'platform' => array(
            'type' => 'selector:platform',
            //验证暂时只支持 true 或 false
            'validator' => array('required' => true, 'number' => true),
            'message' => array('number' => '必需为数字')
        ),
        'image' => array(
            //type image 类型 image:[1,3]，或者 [1]
            'type' => 'image',
            'min_count' => 1,
            'max_count' => 3,
            'min_error_msg' => '至少上传%s张图片',
            'max_error_msg' => '最多只允许上传%s张图片',
            'editable' => true
        ),
    ),
];