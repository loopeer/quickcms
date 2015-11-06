<?php

namespace Loopeer\QuickCms\Models;

use Illuminate\Database\Eloquent\Model;

class ActionLog extends Model
{
    protected $fillable = array('user_id', 'content', 'client_ip');
}
