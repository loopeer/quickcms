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
    'versions_model' => 'Version',
    'versions_index_column' => array(
        'id', 'platform', 'version_code', 'version', 'url', 'message', 'description', 'status',
    ),
    'versions_index_column_name' => array(
        'ID', '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '版本状态', '选项',
    ),

    'feedbacks_model' => 'Feedback',
    'feedbacks_index_column' => array(
        'id', 'account_id', 'content', 'contact', 'version_code', 'version', 'device_id', 'channel_id',
    ),
    'feedbacks_index_column_name' => array(
        'ID', '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式', '选项',
    ),
];