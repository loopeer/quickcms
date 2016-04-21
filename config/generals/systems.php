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
    'middleware' => array('admin.systems'),
    'model_class' => 'Loopeer\QuickCms\Models\System',
    'model_name' => '设置',
    'index_column' => array('id', 'system_key', 'system_value', 'description',),
    'index_column_name' => array('ID', 'system_key', 'system_value', '描述', '选项',),
    'edit_column' => array('system_key', 'system_value', 'description'),
    'edit_column_name' => array('system_key', 'system_value', '描述'),
    'edit_column_detail' => array(
        'system_key' => array(
            'validator' => array('required' => true),
        )
    ),
];