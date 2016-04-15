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
    //系统设置管理
    'create_able' => true,  //是否可创建
    'middleware'=>array(
        'admin.systems'
    ),
    'model_class' => 'Loopeer\QuickCms\Models\System',
    'model_name' => '设置',
    'index_column' => array(
        'id', 'system_key', 'system_value', 'description',
    ),
    'index_column_name' => array(
        'ID', 'system_key', 'system_value', '描述', '选项',
    ),
    'edit_column' => array(
        'system_key', 'system_value', 'description'
    ),
    'edit_column_name' => array(
        'system_key', 'system_value', '描述'
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
            'url' => '/admin/systems',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'edit_column_detail' => array(
        'system_key'=>array(
            'validator'=>array('required' => true),
        )
    ),
];