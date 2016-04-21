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
    //意见反馈
    'curd_action' => array('create' => false, 'edit' => false, 'delete' => false, 'detail' => false),
    'middleware' => array('admin.feedbacks'),
    'model_class' => 'Loopeer\QuickCms\Models\Feedback',
    'model_name' => '意见',
    'index_column' => array('id', 'content', 'version', 'version_code', 'device_id', 'channel_id', 'contact'),
    'index_column_name' => array('ID', '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式'),
    'edit_column' => array('content', 'version_code', 'version', 'device_id', 'channel_id', 'contact'),
    'edit_column_name' => array('反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式'),
];