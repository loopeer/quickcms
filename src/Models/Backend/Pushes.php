<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/22
 * Time: 下午6:03
 */
namespace Loopeer\QuickCms\Models\Backend;

class Pushes extends BaseModel{

    protected $casts = ['app_channel_id' => 'string'];
}