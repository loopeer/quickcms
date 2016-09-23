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

use App\Http\Controllers\Controller;
use Input;
use Log;
use Session;
use DB;
use Loopeer\QuickCms\Models\Role;
use Loopeer\QuickCms\Models\Permission;
use Redirect;
use Response;

class PermissionController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:system');
        $this->middleware('auth.permission:admin.permissions.index');
        parent::__construct();
    }

    public function search() {
        $select_column = ['menus.id','menus.name','menus.display_name','menus.route','parents.display_name','menus.sort','menus.icon','menus.description'];
        $show_column = ['menu_id','menu_name','menu_display_name','menu_route','parent_display_name','menu_sort','menu_icon','menu_description'];
        $tables = ['menus', 'parents'];
        $permissions = DB::table('permissions as menus')->leftJoin('permissions as parents','parents.id','=','menus.parent_id')
            ->where('menus.type', 0)
            ->select('menus.id as menu_id','menus.name as menu_name','menus.display_name as menu_display_name','menus.route as menu_route',
                'parents.display_name as parent_display_name','menus.sort as menu_sort','menus.icon as menu_icon','menus.description as menu_description');
        $ret = self::getMultiTableData($permissions, $select_column, $show_column, $tables);
        return $ret;
    }

    public function index() {
        $message = Session::get('message');
        return view('backend::permissions.index', compact('message', 'permission_list', 'parent_permission_list'));
    }

    public function create() {
        $permission = new Permission();
        $message = Session::get('message');
        $action = route('admin.permissions.store');
        $parent_permission_list = Permission::where('parent_id', 0)->get();
        return view('backend::permissions.create', compact('message','action','parent_permission_list','permission'));
    }

    public function store() {
        $inputs = Input::all();
        $operation_permission = Input::get('operation_permission');
        $permission_id = Input::get('permission_id',null);
        if ($inputs['parent_id'] == 13) {
            $inputs['parent_id'] = 0;
        }
        $inputs['level'] = $inputs['parent_id'] == 0 ? 1 : 2;
        if(is_null($permission_id)){
            array_except($inputs, array('operation_permission'));
            //创建
            $isset = Permission::where('name',$inputs['name'])->first();
            if(is_null($isset)){
                $permission = Permission::create($inputs);
                if(isset($operation_permission) && $operation_permission == 'Y') {
                    $operation = array('create' => '新增', 'edit' => '编辑', 'delete' => '删除', 'show' => '详情', 'changeStatus' => '状态变更');
                    $permissions = [];
                    foreach($operation as $operation_key => $operation_value) {
                        $permissions[] = array(                                                       
                            'name' => str_replace('.index', '', $permission->name) . '.' . $operation_key, 
                            'display_name' => $operation_value,                            
                            'route' => $permission->route . '/' . $operation_key, 
                            'type' => 1,
                            'parent_id' => $permission->id,
                        );
                    }
                    DB::table('permissions')->insert($permissions);
                }
                $message = array('result' => true,'content' => '添加权限成功，重新登陆后即可更新左侧菜单栏');
                return redirect('admin/permissions')->with('message', $message);
            }else{
                $message = array('result' => false,'content' => '添加权限失败,此权限名称已存在');
                return Redirect::back()->with('message', $message);
            }
        }else{
            //编辑
            $permission = Permission::find($permission_id);
            $isset = Permission::whereNotIn('id',array($permission_id))->where('name',$inputs['name'])->first();
            if(is_null($isset)){
                $permission->update($inputs);
                $message = array('result' => true,'content'=>'编辑权限成功');
                return redirect('admin/permissions')->with('message', $message);
            }else{
                $message = array('result' => false,'content'=>'编辑权限失败,此权限名称已存在');
                return Redirect::back()->with('message', $message);
            }
        }

    }

    public function init($id) {
        $permission = Permission::find($id);
        $operation = array('create' => '新增', 'edit' => '编辑', 'delete' => '删除', 'show' => '详情', 'changeStatus' => '状态变更');
        $permissions = [];
        foreach($operation as $operation_key => $operation_value) {
            $permissions[] = array(
                'name' => str_replace('.index', '', $permission->name) . '.' . $operation_key,
                'display_name' => $operation_value,
                'route' => $permission->route . '/' . $operation_key,
                'type' => 1,
                'parent_id' => $permission->id,
            );
        }
        DB::table('permissions')->insert($permissions);
        return Redirect::to('/admin/permission/' . $id . '/indexPermission');
    }

    public function edit($id) {
        $permission = Permission::find($id);
        $message = Session::get('message');
        $action = route('admin.permissions.store',array('permission_id'=>$id));
        $parent_permission_list = Permission::where('parent_id', 0)->get();
        return view('backend::permissions.create', compact('message','action','parent_permission_list','permission'));
    }

    public function destroy($id) {
        $permission = Permission::find($id);
        $menu = Permission::where('parent_id',$id)->first();
        if(is_null($menu)){
            $result = true;
            $content = '删除权限成功';
            $permission->delete();
        }else{
            $result = false;
            $content = '删除权限失败，不能删除已经被关联的一级权限';
        }
        $res = array('result' => $result,'content' => $content);
        return $res;
    }
}
