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
use Redirect;
use Auth;
use Hash;

class UserController extends BaseController {

    public function __construct(){
        //$this->middleware('auth.permission:system');
        //$this->middleware('auth.permission:admin.users.index');
        parent::__construct();
    }

    public function search() {
        $select_column = ["COALESCE(roles.display_name,'')",'users.id','users.name','users.email','users.created_at','users.last_login','users.status'];
        $show_column = ['user_id','user_name','email','role_name','user_created_at','last_login','status'];
        $tables = ['users','roles'];
        $users = DB::table('users')
            ->select('users.id as user_id','users.name as user_name','users.email as email','users.created_at as user_created_at',
                'users.last_login as last_login','users.status as status','roles.display_name as role_name')
            ->leftJoin('role_user','users.id','=','role_user.user_id')
            ->leftJoin('roles','roles.id','=','role_user.role_id');
        $ret = self::getMultiTableData($users, $select_column, $show_column, $tables);
        return $ret;
    }

    public function index() {
        $message = Session::get('message');
        return view('backend::users.index', compact('message'));
    }

    public function create() {
        $roles = Role::all();
        $action = route('admin.users.store');
        $user = new User();
        $message = Session::get('message');
        return view('backend::users.create',compact('roles','action','user','message'));
    }

    public function store() {
        $inputs = Input::all();
        if (!isset($inputs['user_id'])) {
            //创建
            $isset = User::where('email',$inputs['email'])->first();
            if(is_null($isset)){
                $user = new User();
                $user->email = $inputs['email'];
                $user->password = Hash::make($inputs['password']);
                $user->name = $inputs['name'];
                $result = $user->save();
                $user->attachRole(Input::get('role_id'));
                $message = array('result' => $result, 'content' => $result ? '添加用户成功' : '添加用户失败');
            }else{
                $message = array('result' => false,'content' => '创建用户失败,此用户名已存在,请重新填写信息');
                return Redirect::back()->with('message', $message);
            }

        } else {
            //编辑
            $user = User::find($inputs['user_id']);
            if ($inputs['password'] != '') {
                $user->password = Hash::make($inputs['password']);
            }
            $user->name = $inputs['name'];
            $result = $user->save();
            $message = array('result' => $result, 'content' => $result ? '编辑用户成功' : '编辑用户失败');
        }
        return redirect('admin/users')->with('message', $message);
    }

    public function edit($id) {
        $roles = Role::all();
        $action = route('admin.users.store',array('user_id' => $id));
        $user = User::find($id);
        $message = Session::get('message');
        return view('backend::users.create', compact('roles','action','user','message'));
    }

    public function update() {
        $user = Auth::admin()->get();
        $message = Session::get('message');
        $image = array(
            'name' => 'image',
            'min_count' => 1,
            'max_count' => 1,
            'min_error_msg' => '至少上传%s张图片',
            'max_error_msg' => '最多只允许上传%s张图片',
            'editable' => true
        );
        return view('backend::users.update', compact('user','message', 'image'));   
    }

    public function profile() {
        $user = Auth::admin()->get();
        $inputs = Input::all();
        $user->name = $inputs['name'];
        if($inputs['password'] != '') {
            $user->password = Hash::make($inputs['password']);
        }
        $user->avatar = $inputs['image'][0];
        $user->save();
        $message = array('result' => true, 'content' => '保存成功');
        return redirect('admin/users/update')->with('message', $message);
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

    }

    public function checkEmail() {
        $email = Input::get('email');
        $isset = User::where('email', '=', $email)->first();
        if (is_null($isset)) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function show() {

    }

}
