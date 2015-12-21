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

    'feedbacks_model_class' => 'Loopeer@QuickCms@Models@Feedback',
    'feedbacks_model_name' => '意见',
    'feedbacks_index_column' => array(
        'id', 'account_id', 'content', 'contact', 'version_code', 'version', 'device_id', 'channel_id',
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

    'versions_edit_column_detail' => array(
        'published_at'=>array(
            //type 暂时只支持 date
            'type'=>'date',
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true)
        ),
        'platform'=>array(
            //type 暂时只支持 date
            'type'=>'text',
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true)
        )

    )
];