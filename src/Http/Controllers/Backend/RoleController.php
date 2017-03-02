<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/10
 * Time: 上午10:12
 */
namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Loopeer\QuickCms\Models\Backend\Permission;
use Loopeer\QuickCms\Models\Backend\PermissionRole;
use Loopeer\QuickCms\Models\Backend\Role;
use Input;

class RoleController extends FastController {

    public function permissions($id) {
        $role = Role::find($id);
        $perents = Permission::with('menus')->where('parent_id',0)->get();
        $permission_ids = PermissionRole::where('role_id',$role->id)->lists('permission_id')->all();
        return view('backend::roles.permission', compact('perents','role','permission_ids'));
    }

    public function savePermissions($id){
        $role = Role::find($id);
        $inputs = Input::all();
        unset($inputs['_token']);
        $permission_ids = array_values($inputs);
        $role->perms()->sync($permission_ids);
        return 1;
    }
}
