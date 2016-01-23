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
    'create_able' => true,  //是否可创建
    'middleware'=>array(
        'admin.roles'
    ),
    'model_class' => 'Loopeer\QuickCms\Models\Role',
    'model_name' => '角色',
    'index_select_column' => array(
        'roles.id','roles.name','roles.display_name','roles.description'
    ),
    'index_column' => array(
        'id','name','display_name','description'
    ),
    'index_column_name' => array(
        '编号', '名称', '显示名称', '描述','操作'
    ),
    'edit_column' => array(
        'name', 'display_name', 'description'
    ),
    'edit_column_name' => array(
        '名称', '显示名称', '描述'
    ),
    // 下拉按钮配置
    'table_action' => array(
        array(
            'default_show' => true,
            'type' => 'dialog',
            'name' => 'edit_permission',
            'target' => 'edit_permission',
            'dialog_title' => '分配权限',
            'display_name' => '分配权限',
            'has_divider' => true,
            'url' => '/admin/roles/permissions/',
            'form' => array(
                'form_id' => 'smart-form-permissions',
                'submit_id' => 'confirmPermission',
                'success_msg' => '分配成功',
                'failure_msg' => '分配失败'
            ),
        ),
        array(
            'default_show' => true,
            'type' => 'edit',
            'name' => 'edit_btn',
            'display_name' => '编辑',
            'has_divider' => true
        ),
        array(
            'default_show' => true,
            'type' => 'confirm',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'method' => 'delete',
            'url' => '/admin/roles',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'edit_column_detail' => array(
        'name'=>array(
            'validator'=>array('required'=>true),
        ),
        'display_name'=>array(
            'validator'=>array('required'=>true),
        ),
    ),
];