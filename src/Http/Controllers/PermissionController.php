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
        $this->middleware('auth.permission:admin.permissions');
        parent::__construct();
    }

    public function search() {
        $search = Input::get('search')['value'];
        $order = Input::get('order')['0'];
        $length = Input::get('length');
        $select_column = ['menus.id','menus.name','menus.display_name','menus.route','parents.display_name','menus.sort','menus.icon','menus.description'];
        $show_column = ['menu_id','menu_name','menu_display_name','menu_route','parent_display_name','menu_sort','menu_icon','menu_description'];
        $order_sql = $show_column[$order['column']] . ' ' . $order['dir'];
        $str_column = self::setTablePrefix(implode(',', $select_column), ['menus']);
        self::setCurrentPage();
        $permissions = DB::table('permissions as menus')->leftJoin('permissions as parents','parents.id','=','menus.parent_id')
        ->select('menus.id as menu_id','menus.name as menu_name','menus.display_name as menu_display_name','menus.route as menu_route',
            'parents.display_name as parent_display_name','menus.sort as menu_sort','menus.icon as menu_icon','menus.description as menu_description')
            ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->orderByRaw($order_sql)
            ->paginate($length);

        $ret = self::queryPage($show_column, $permissions);
        return Response::json($ret);
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
        $permission_id = Input::get('permission_id',null);
        $inputs['level'] = $inputs['parent_id'] == 0 ? 1 : 2;
        if(is_null($permission_id)){
            //创建
            $isset = Permission::where('name',$inputs['name'])->first();
            if(is_null($isset)){
                Permission::create($inputs);
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