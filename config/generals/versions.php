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
    'create_able' => true,  //是否可创建
    'middleware'=>array(
        'admin.versions'
    ),
    'model_class' => 'Loopeer\QuickCms\Models\Version',
    'model_name' => '版本',
//    'index_select_column' => array(
//        'versions.id', 'versions.platform', 'versions.version_code', 'versions.version', 'versions.url', 'versions.message',
//        'versions.description', 'versions.status','versions.published_at',
//    ),
    'index_column' => array(
        'id', 'platform', 'version_code', 'version', 'url', 'message', 'description', 'status', 'published_at',
    ),
    'index_column_name' => array(
        'ID', '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '版本状态', '发布时间', '选项',
    ),
//    'index_where' => array(
//        array('column' => 'news_type', 'operator' => '=', 'value' => 2),
//        array('column' => 'news_type', 'operator' => 'whereIn', 'value' => [1,2]),
//        array('column' => 'news_type', 'operator' => 'whereNotIn', 'value' => [1,2]),
//        array('column' => 'news_type', 'operator' => 'whereBetween', 'value' => [1,2]),
//        array('column' => 'news_type', 'operator' => 'whereNotBetween', 'value' => [1,2]),
//        array('column' => 'news_type', 'operator' => 'whereNull'),
//        array('column' => 'news_type', 'operator' => 'whereNotNull'),
//    ),
    'edit_column' => array(
        'platform', 'version_code', 'version', 'url', 'description', 'published_at', 'status'
    ),
    'edit_column_name' => array(
        '发布平台', '版本号', '版本名称', '下载地址', '版本描述', '发布时间','版本状态',
    ),
    'index_column_rename' => array(
        // 字段名配置
        'status' => array(
            //正常状态显示替换
            'type'=>'normal',
            'param'=>array(
                //数据库值为键名，显示内容为键值
                0 => array(
                    'value' => '<span class="label label-default">未上线</span>',
                    'action_name' => 'online_btn'
                ),
                1 => array(
                    'value' => '<span class="label label-success">已上线</span>',
                    'action_name' => 'offline_btn'
                )
            ),
        ),
        // 字段名配置
        'version_code'=>array(
            //点击数字出现模态框
            'type' => 'dialog',
            'param'=>array(
                // a标签name名称
                'name' => 'test_btn',
                //模态框名称
                'target' => 'test_dialog',
                //模态框标题
                'dialog_title' => 'Modal',
                //模态框路由，结尾的'/'不能省略，url后会传递id值，路由需配置
                'url' => '/admin/test/detail/'
            )
        )
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
            'default_show' => false,
            'type' => 'confirm',
            'name' => 'online_btn',
            'display_name' => '上线',
            'has_divider' => true,
            'method' => 'get',
            'url' => '/admin/versions/changeStatus',
            'confirm_msg' => '确定要上线吗?',
            'success_msg' => '操作成功',
            'failure_msg' => '操作失败'
        ),
        array(
            'default_show' => false,
            'type' => 'confirm',
            'name' => 'offline_btn',
            'display_name' => '下线',
            'has_divider' => true,
            'method' => 'get',
            'url' => '/admin/versions/changeStatus',
            'confirm_msg' => '确定要下线吗?',
            'success_msg' => '操作成功',
            'failure_msg' => '操作失败'
        ),
        array(
            'default_show' => true,
            'type' => 'confirm',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'method' => 'delete',
            'url' => '/admin/versions',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'edit_column_detail' => array(
        'published_at'=>array(
            //type 暂时只支持 date/time/selector，selector 需要配置 key 值，例：'type'=>'selector:key'
            'type'=>'date',
            //验证暂时只支持 true 或 false 类型
            'validator'=>array('required'=>true),
            //message 为选填
            'message'=>array('required'=>'必需填写时间发布时间')
        ),
        'platform'=>array(
            'type'=>'selector:platform',
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true, 'number'=>true),
            'message'=>array('number'=>'必需为数字')
        )
    ),
];