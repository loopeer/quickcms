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
namespace Loopeer\QuickCms\Http\Controllers;

use Loopeer\QuickCms\Models\Permission;
use Loopeer\QuickCms\Models\User;
use Loopeer\QuickCms\Models\ActionLog;
use View;
use Input;
use Log;
use Session;
use DB;
use Redirect;
use Response;
use Auth;
use Illuminate\Http\Request;

class IndexController extends BaseController {

    public function index() {
        $user = Auth::admin()->get();
        $user_action_log = ActionLog::where('user_id',$user->id)->orderBy('id','desc')->first();
        $count_user = User::count();
        return view('backend::index',compact('user','user_action_log','count_user'));
    }

    public function getLogin(){
        $message = Session::get('message');
        return view('backend::login',compact('message'));
    }

    public function postLogin(Request $request){
        $email = Input::get('email');
        $remember = Input::get('remember',0);
        $user = User::where('email',$email)->first();
        Auth::admin()->login($user,$remember);
        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
        $this->getMenus($user);
        ActionLog::create(array(
            'user_id' => $user->id,
            'content' => config('quickcms.action_log.login'),
            'client_ip' => $request->ip()
        ));
        return $this->getIndex();
    }

    public function logout(){
        Auth::admin()->logout();
        Session::flush();
        $message = array('result' => true,'content' => '退出成功');
        return redirect('/admin/login')->with('message',$message);
    }

    private function getIndex(){
        $user = Auth::admin()->get();
        $permissions = Permission::whereNotNull('route')->where('route','!=','')->orderBy('level')->where('route','!=','#')->orderBy('sort');
        $permission_routes = $permissions->lists('route','name');
        if(count($permission_routes) > 0){
            foreach($permission_routes as $name => $route){
                if($user->can($name)){
                    return redirect($route);
                }
            }
        }
        return redirect('/admin/index');
    }

    public function getMenus($user){
        $menus = Permission::with('menus')->where('parent_id', 0)->orderBy('sort')->get();
        if(isset($user)){
            foreach($menus as $key=>$menu){
                $items = Permission::where('parent_id',$menu->id)->get();
                if (!is_null($items) && count($items)>0) {
                    foreach ($items as $item_key => $item) {
                        if (!$user->can($item->name)) {
                            unset($menus[$key]['menus'][$item_key]);
                        }
                    }
                }
                if (!$user->can($menu->name)) {
                    unset($menus[$key]);
                }
            }
        }
        Session::put('menu', json_decode($menus, true));
    }

    public function getLoginLog(){
        $search = Input::get('search')['value'];
        $order = Input::get('order')['0'];
        $length = Input::get('length');
        $select_column = ['action_logs.id','users.email','action_logs.client_ip','action_logs.created_at'];
        $show_column = ['id','email','client_ip','created_at'];
        $order_sql = $show_column[$order['column']] . ' ' . $order['dir'];
        $str_column = self::setTablePrefix(implode(',', $select_column), ['users','action_logs']);
        self::setCurrentPage();
        $users = ActionLog::where('content',config('quickcms.action_log.login'))->orderBy('created_at','desc')
            ->select('users.email as email','action_logs.id','action_logs.client_ip','action_logs.created_at')
            ->leftJoin('users','users.id','=','action_logs.user_id')
            ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->orderByRaw($order_sql)
            ->paginate($length);
        $ret = self::queryPage($show_column, $users);
        return Response::json($ret);
    }
}