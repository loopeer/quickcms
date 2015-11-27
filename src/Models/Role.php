<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/11
 * Time: 上午11:38
 */
namespace Loopeer\QuickCms\Models;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['id', 'name', 'display_name','description'];

    public function users(){
        return $this->belongsToMany(config('auth.multi-auth.admin.model'),config('entrust.role_user_table'));
    }
}