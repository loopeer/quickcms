<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/22
 * Time: 下午1:42
 */
namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Loopeer\QuickCms\Models\Backend\Permission;
use Loopeer\QuickCms\Models\Backend\User;
use Input;
use Log;
use Session;
use DB;
use Auth;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class IndexController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth.permission:admin.index',['only' => ['index','getLoginLog']]);
    }

    public function index()
    {
        $user = Auth::admin()->get();
        $count_user = Cache::rememberForever('count_user', function() {
            return User::count();
        });
        return view('backend::index',compact('user', 'count_user'));
    }

    public function getLogin()
    {
        $message = Session::get('message');
        return view('backend::login',compact('message'));
    }

    public function postLogin(Request $request)
    {
        $email = Input::get('email');
        $remember = Input::get('remember', 1);
        $user = User::where('email',$email)->first();
        Auth::admin()->login($user,$remember);
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        return redirect('/admin/index')->with('user', $user);
    }

    public function logout()
    {
        Auth::admin()->logout();
        Session::forget('menu');
        Session::forget('permissions');
        Session::forget('business_id');
        $message = array('result' => true,'content' => '退出成功');
        return redirect('/admin/login')->with('message',$message);
    }

    public function getIndex()
    {
        $user = Auth::admin()->get();
        $permissions = Permission::whereNotNull('route')->where('route','!=','')->orderBy('level')->where('route','!=','#')->orderBy('sort');
        $permission_routes = $permissions->lists('route','name');
        if (count($permission_routes) > 0) {
            foreach ($permission_routes as $name => $route) {
                if ($user->can($name)) {
                    return redirect($route);
                }
            }
        }
        return redirect('/admin/index');
    }
}
