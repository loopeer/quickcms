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

use Loopeer\QuickCms\Models\Role;
use Loopeer\QuickCms\Models\RoleUser;
use Input;
use Log;
use Session;
use Loopeer\QuickCms\Models\User;
use Response;
use DB;

class UserController extends BaseController {

    public function __construct(){
        $this->middleware('auth.permission:system');
        $this->middleware('auth.permission:admin.users');
    }

    public function search() {
        $search = Input::get('search')['value'];
        $order = Input::get('order')['0'];
        $length = Input::get('length');
        $select_column = ["COALESCE(ss_role.display_name,'')",'ss_user.id','ss_user.name','ss_user.email','ss_user.created_at','ss_user.last_login','ss_user.status'];
        $show_column = ['user_id','user_name','email','role_name','user_created_at','last_login','status'];
        $order_sql = $show_column[$order['column']] . ' ' . $order['dir'];
        $str_column = implode(',', $select_column);
        self::setCurrentPage();
        $users = DB::table('users as user')->leftJoin('role_user as ru','user.id','=','ru.user_id')
            ->leftJoin('roles as role','role.id','=','ru.role_id')
            ->select('user.id as user_id','user.name as user_name','user.email as email','user.created_at as user_created_at',
                'user.last_login as last_login','user.status as status','role.display_name as role_name')
            ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->orderByRaw($order_sql)
            ->paginate($length);

        $ret = self::queryPage($show_column, $users);
        return Response::json($ret);
    }

    public function index() {
        $message = Session::get('message');
        return view('backend::users.index', compact('message'));
    }

    public function create() {
        $roles = Role::all();
        return view('backend::users.create',compact('roles'));
    }

    public function store() {
        $inputs = Input::all();
        $password = sha1($inputs['password'].config('api.admin_pwd_salt'));
        $role_id = Input::get('role_id');
        $user = new User();
        $user->email = $inputs['email'];
        $user->password = $password;
        $user->name = $inputs['name'];
        $user->save();
        $user->attachRole($role_id);
        $result = empty($user) ? false : true;
        $message = array('result' => $result, 'content' => $result ? '添加用户成功' : '添加用户失败');
        return redirect('admin/users')->with('message', $message);
    }


    public function edit() {
        return view('backend::users.create');
    }

    public function destroy($id) {
        $flag = User::find($id)->delete();
        return $flag ? 1 : 0;
    }

    public function changeStatus($id){
        $user = User::find($id);
        if($user->status == 0){
            $user->status = 1;
            $content = '此用户已被启用';
        }elseif($user->status == 1){
            $user->status = 0;
            $content = '此用户已被禁用';
        }
        $user->save();
        $res = array('result' => true,'content' => $content);
        return $res;
    }

    public function getRole($id){
        $user = User::find($id);
        $role_user = RoleUser::where('user_id',$user->id)->first();
        $role_id = isset($role_user) ? $role_user->role_id : null;
        $roles = Role::all();
        $action = route('admin.users.role',array('user_id'=>$id));
        return view('backend::users.role',compact('role_id','roles','action'));
    }

    public function saveRole(){
        $user_id = Input::get('user_id');
        $role_id = Input::get('role_id');
        $user = User::find($user_id);
        RoleUser::where('user_id',$user_id)->delete();
        $user->attachRole($role_id);
        $res = array('result' => true,'content' => '分配用户角色成功');
        return $res;
    }

}