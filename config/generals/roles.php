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
    //角色管理
    'curd_action' => array('detail' => false),
    'middleware' => array('admin.roles'),
    'model_class' => 'Loopeer\QuickCms\Models\Role',
    'model_name' => '角色',
    'index_column' => array('id', 'name', 'display_name', 'description'),
    'index_column_name' => array('编号', '名称', '显示名称', '描述'),
    'edit_column' => array('name', 'display_name', 'description'),
    'edit_column_name' => array('名称', '显示名称', '描述'),
    'table_action' => array(
        array(
            'type' => 'dialog',
            'name' => 'edit_permission',
            'target' => 'edit_permission',
            'method' => 'get',
            'permission' => 'admin.roles.permissions',
            'dialog_title' => '分配权限',
            'display_name' => '分配权限',
            'url' => '/admin/roles/permissions/',
            'form' => array(
                'form_id' => 'smart-form-permissions',
                'submit_id' => 'confirmPermission',
                'success_msg' => '分配权限成功，重新登陆后即可更新左侧菜单栏',
                'failure_msg' => '分配失败'
            ),
        )
    ),
    'edit_column_detail' => array(
        'name' => array(
            'validator' => array('required' => true),
        ),
        'display_name' => array(
            'validator' => array('required' => true),
        ),
    ),
];
