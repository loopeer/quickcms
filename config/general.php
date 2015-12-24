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
    'versions_createable' => true,  //是否可创建
    'versions_model_class' => 'Loopeer@QuickCms@Models@Version',
    'versions_model_name' => '版本',
    'versions_index_column' => array(
        'id', 'platform', 'version_code', 'version', 'url', 'message', 'description', 'status','published_at',
    ),
    'versions_index_column_name' => array(
        'ID', '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '版本状态', '发布时间', '选项',
    ),
    'versions_edit_column' => array(
        'platform', 'version_code', 'version', 'url', 'message', 'description', 'published_at', 'status',
    ),
    'versions_edit_column_name' => array(
        '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '发布时间','版本状态',
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
        'message'=>array(
            //type image 类型 image:[1,3]，或者 [1]
            'type'=>'image:[1,3]',
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true),
            'message'=>array('required'=>'必需上传图片')
        )
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
    'feedbacks_model_class' => 'Loopeer@QuickCms@Models@Feedback',
    'feedbacks_model_name' => '意见',
    'feedbacks_index_column' => array(
        'id', 'content', 'version', 'version_code', 'device_id', 'channel_id', 'contact',
    ),
    'feedbacks_index_column_name' => array(
        'ID', '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式', '选项',
    ),
    'feedbacks_edit_column' => array(
        'content', 'version_code', 'version', 'device_id', 'channel_id', 'contact'
    ),
    'feedbacks_edit_column_name' => array(
        '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式'
    ),
    'feedbacks_table_action' => array(
        array(
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false
        )
    ),
];