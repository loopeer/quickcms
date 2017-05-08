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

use Illuminate\Support\Facades\Response;
use Loopeer\QuickCms\Models\Backend\ActionLog;
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
        $last_login_log = ActionLog::where('type', ActionLog::LOGIN_TYPE)
            ->orderBy('created_at','desc')
            ->skip(1)->first();
        exec('cd ' . base_path() . '&& /usr/bin/php /usr/local/bin/composer -i info loopeer/quickcms', $result, $return);
        if ($return == 0) {
            $info = [];
            collect($result)->each(function ($item) use (&$info) {
                if (str_contains($item, 'versions')) {
                    $info['versions'] = $item;
                }
                if (str_contains($item, ['source', 'quickcms.git'])) {
                    $info['source'] = $item;
                }
            });
            $version = isset($info['versions']) ? trim(explode(':', $info['versions'])[1]) : null;
            $commit = isset($info['source']) ? trim(substr(strrchr($info['source'], ' '), 1)) : null;
        }
        return view('backend::index',compact('user', 'count_user', 'version', 'commit', 'last_login_log'));
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

    public function getLoginLog()
    {
        $search = trim(Input::get('search')['value']);
        $order = Input::get('order')['0'];
        $length = Input::get('length');
        $select_column = ['created_at','user_name','ip' ,'system', 'browser'];
        $show_column = ['created_at','user_name','ip' ,'system', 'browser'];
        $order_sql = $show_column[$order['column']] . ' ' . $order['dir'];
        $str_column = self::setTablePrefix(implode(',', $select_column));
        self::setCurrentPage();
        $users = ActionLog::where('type', ActionLog::LOGIN_TYPE)->orderBy('created_at','desc')
            ->select('created_at','user_name','ip' ,'system', 'browser')
            ->whereRaw("concat_ws(" . $str_column . ") like '%" . $search . "%'")
            ->orderByRaw($order_sql)
            ->paginate($length);
        $ret = self::queryPage($show_column, $users);
        return Response::json($ret);
    }
}
