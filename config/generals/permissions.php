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
    // 权限管理
    'create_able' => true,  //是否可创建
    'middleware'=>array(
        'admin.permissions'
    ),
    'model_class' => 'Loopeer\QuickCms\Models\Permission',
    'model_name' => '权限',
    'index_select_column' => array(
        'permissions.id', 'permissions.name', 'permissions.display_name', 'permissions.route'
    ),
    'index_multi' => true,
    'index_multi_join' => array(
        ['permissions as parents', 'parents.id', '=', 'menus.parent_id'],
    ),
    'index_multi_column' => array(
        'menus.id as menu_id',
        'permissions.name as menu_name',
        'permissions.display_name as menu_display_name',
        'permissions.route as menu_route',
        'parents.display_name as parent_display_name',
        'permissions.sort as menu_sort',
        'permissions.icon as menu_icon',
        'permissions.description as menu_description'
    ),
    'index_column' => array(
        'menu_id', 'menu_name', 'menu_display_name', 'menu_name', 'parent_display_name', 'menu_sort', 'menu_icon', 'menu_description'
    ),
    'index_column_name' => array(
        '编号', '名称', '显示名称', '路由', '上级权限', '排序', '图标', '描述', '操作'
    ),
    'edit_column' => array(
        'name', 'display_name', 'parent_id', 'route', 'sort', 'icon', 'description'
    ),
    'edit_column_name' => array(
        '权限名称 如：admin.permissions.create或者permissions#',
        '权限显示名称',
        '父权限',
        '权限路由 如：/admin/permissions/create,如果为一级菜单可以为：#',
        '排序',
        '显示图标',
        '权限描述',
    ),
    // 下拉按钮配置
    'table_action' => array(
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
            'url' => '/admin/permissions',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'edit_column_detail' => array(
        'parent_id'=>array(
            //type 暂时只支持 date/time/selector，selector 需要配置 key 值，例：'type'=>'selector:key'
            'type'=>'selector:parent_permissions',
            //验证暂时只支持 true 或 false 类型
            'validator'=>array('required'=>true),
            //message 为选填
//            'message'=>array('required'=>'必需填写时间发布时间')
        ),
        'name'=>array(
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true),
        ),
        'display_name'=>array(
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true),
        ),
    ),
];