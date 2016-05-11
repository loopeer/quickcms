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
namespace Loopeer\QuickCms\Http\Controllers;

use Loopeer\QuickCms\Models\Permission;
use Loopeer\QuickCms\Models\PermissionRole;
use Loopeer\QuickCms\Models\RoleUser;
use Input;
use Log;
use Session;
use DB;
use Loopeer\QuickCms\Models\Role;
use Redirect;
use Response;
use Illuminate\Http\Request;

class RoleController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:system');
        $this->middleware('auth.permission:admin.roles.index');
        parent::__construct();
    }

    public function search(){
        $ret = self::simplePage(['id','name','display_name','description'], new Role());
        return Response::json($ret);
    }

    public function index() {
        $message = Session::get('message');
        $role_list = Role::get();
        return view('backend::roles.index', compact('message', 'role_list'));
    }

    public function create() {
        $role = new Role();
        $message = Session::get('message');
        $action = route('admin.roles.store');
        return view('backend::roles.create', compact('message','action','role'));
    }

    public function store() {
        $inputs = Input::all();
        $role_id = Input::get('role_id',null);
        if(is_null($role_id)){
            $isset = Role::where('name',$inputs['name'])->first();
            if(is_null($isset)){
                Role::create($inputs);
                $message = array('result' => true , 'content' => '添加角色成功');
                return redirect('admin/roles')->with('message', $message);
            }else{
                $message = array('result' => false,'content' => '添加角色失败,此角色名称已存在');
                return Redirect::back()->with('message', $message);
            }
        }else{
            $role = Role::find($role_id);
            $isset = Role::whereNotIn('id',array($role_id))->where('name',$inputs['name'])->first();
            if(is_null($isset)){
                $role->update($inputs);
                $message = array('result' => true,'content'=>'编辑角色成功');
                return redirect('admin/roles')->with('message', $message);
            }else{
                $message = array('result' => false,'content'=>'编辑角色失败,此权限名称已存在');
                return Redirect::back()->with('message', $message);
            }
        }

    }

    public function edit($id) {
        $message = Session::get('message');
        $role = Role::find($id);
        $action = route('admin.roles.store',array('role_id'=>$id));
        return view('backend::roles.create', compact('message','action','role'));
    }


    public function destroy($id) {
        $role = Role::find($id);
        $role_user = RoleUser::where('role_id',$id)->first();
        if(is_null($role_user)){
            $result = true;
            $content = '删除角色成功';
            $role->delete();
        }else{
            $result = false;
            $content = '删除角色失败,不能删除已经被用户关联的角色';
        }
        $res = array('result' => $result,'content'=> $content);
        return $res;
    }

    public function permissions($id) {
        $role = Role::find($id);
        $perents = Permission::with('menus')->where('parent_id',0)->get();
        $permission_ids = PermissionRole::where('role_id',$role->id)->lists('permission_id')->all();
        return view('backend::roles.permissions', compact('perents','role','permission_ids'));
    }

    public function savePermissions($id){
        $role = Role::find($id);
        $inputs = Input::all();
        unset($inputs['_token']);
        $permission_ids = array_values($inputs);
        $role->perms()->sync($permission_ids);
//        $message = array('result' => true,'content'=>'分配权限成功，重新登陆后即可更新左侧菜单栏');
        return 1;
    }
}
