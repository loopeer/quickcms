<?php

namespace Loopeer\QuickCms\Models;

use Loopeer\QuickCms\Models\Backend\BaseModel;

class ActionLog extends BaseModel
{
    protected $fillable = array('user_id', 'content', 'client_ip');
}
