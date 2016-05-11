<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/11
 * Time: 上午11:39
 */
namespace Loopeer\QuickCms\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $fillable = ['id','parent_id', 'name', 'display_name','route','sort','icon','description','level','type'];

    public function menus() {
        return $this->hasMany('Loopeer\QuickCms\Models\Permission', 'parent_id')->orderBy('sort')->where('type', 0);
    }

    public function parent(){
        return $this->belongsTo('Loopeer\QuickCms\Models\Permission','parent_id');
    }
}
