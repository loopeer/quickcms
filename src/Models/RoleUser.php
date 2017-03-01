<?php

namespace Loopeer\QuickCms\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';
    protected $fillable = ['user_id', 'role_id'];
    public $timestamps = false;
    protected $primaryKey = 'user_id';
}
