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
    'versions_create_able' => true,  //是否可创建
    'versions_model_class' => 'Loopeer\QuickCms\Models\Version',
    'versions_model_name' => '版本',
    'versions_index_column' => array(
        'id', 'platform', 'version_code', 'version', 'url', 'message', 'description', 'status','published_at',
    ),
    'versions_index_column_name' => array(
        'ID', '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '版本状态', '发布时间', '选项',
    ),
    'versions_edit_column' => array(
        'platform', 'version_code', 'version', 'url', 'image', 'description', 'published_at', 'status'
    ),
    'versions_edit_column_name' => array(
        '发布平台', '版本号', '版本名称', '下载地址', '图片', '版本描述', '发布时间','版本状态',
    ),
    'versions_index_column_rename' => array(
        'status' => array(
            0 => array(
                'value' => '<span class="label label-default">未上线</span>',
                'action_name' => 'online_btn'
            ),
            1 => array(
                'value' => '<span class="label label-success">已上线</span>',
                'action_name' => 'offline_btn'
            )
        )
    ),

    'versions_table_action' => array(
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
    'versions_edit_column_detail' => array(
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

    'versions_status_change'=>true,
    'versions_column_display'=>array(
        //暂时只支持两种状态
        'status'=>array(
            '0'=>'测试版',
            '1'=>'正式版'
        )
    ),

    //意见反馈
    'feedbacks_createable' => false,
    'feedbacks_sort' => ['created_at', 'desc'],
    'feedbacks_create_able' => false,
    'feedbacks_model_class' => 'Loopeer\QuickCms\Models\Feedback',
    'feedbacks_model_name' => '意见',
    'feedbacks_index_column' => array(
        'id', 'content', 'version', 'version_code', 'device_id', 'channel_id', 'contact', 'created_at'
    ),
    'feedbacks_index_column_name' => array(
        'ID', '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式', '反馈时间', '选项',
    ),
    'feedbacks_edit_column' => array(
        'content', 'version_code', 'version', 'device_id', 'channel_id', 'contact'
    ),
    'feedbacks_edit_column_name' => array(
        '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式'
    ),
    'feedbacks_table_action' => array(
        array(
            'default_show' => true,
            'type' => 'confirm',
            'method' => 'delete',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败',
            'url' => '/admin/feedbacks',
        )
    ),
];