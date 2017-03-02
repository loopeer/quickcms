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

use Input;
use Log;
use Session;
use DB;
use Loopeer\QuickCms\Models\Backend\Permission;
use Redirect;
use Response;

class OperationPermissionController extends BaseController {

    public function __construct(){
        parent::__construct();
    }

    public function search($id) {
        $length = Input::get("length");
        //$ret = self::simplePage(['id', 'name', 'display_name', 'route', 'description'], new Permission());
        self::setCurrentPage();
        $paginate = Permission::where('parent_id', $id)->paginate($length);
        $ret = self::queryPage(['id', 'name', 'display_name', 'route', 'description'], $paginate);
        return Response::json($ret);
    }

    public function index($id) {
        $message = Session::get('message');
        return view('backend::permissions.permission_index', compact('message','id'));
    }

    public function create($id) {
        $permission = new Permission();
        $message = Session::get('message');
        return view('backend::permissions.permission_create', compact('message','permission', 'id'));
    }

    public function store($id) {
        $inputs = Input::all();
        $permission_id = Input::get('permission_id',null);
        $inputs['parent_id'] = $id;
        $inputs['type'] = 1;

        if(empty($permission_id)){
            //创建
            $isset = Permission::where('name',$inputs['name'])->first();
            if(is_null($isset)){
                Permission::create($inputs);
                $message = array('result' => true,'content' => '添加操作权限成功，重新登陆后即可更新左侧菜单栏');
                return redirect('admin/permissions/' . $id . '/indexPermission')->with('message', $message);
            }else{
                $message = array('result' => false,'content' => '添加操作权限失败,此权限名称已存在');
                return Redirect::back()->with('message', $message);
            }
        }else{
            //编辑
            $permission = Permission::find($permission_id);
            $isset = Permission::whereNotIn('id',array($permission_id))->where('name',$inputs['name'])->first();
            if(is_null($isset)){
                $permission->update($inputs);
                $message = array('result' => true,'content'=>'编辑操作权限成功');
                return redirect('admin/permissions/' . $id . '/indexPermission')->with('message', $message);
            }else{
                $message = array('result' => false,'content'=>'编辑操作权限失败,此权限名称已存在');
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

    public function edit($id, $permission_id) {
        $permission = Permission::find($permission_id);
        $message = Session::get('message');
        return view('backend::permissions.permission_create', compact('message','permission','id'));
    }

    public function destroy($id, $permission_id) {
        $permission = Permission::find($permission_id);
        $result = true;
        $content = '删除权限成功';
        $permission->delete();
        $res = array('result' => $result,'content' => $content);
        return $res;
    }
}
