<?php

namespace Loopeer\QuickCms\Models;

class ActionLog extends BaseModel
{
    protected $fillable = array('user_id', 'content', 'client_ip');
}
