<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-10-22
 * Time: 下午2:04
 */
namespace Loopeer\QuickCms\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Log;
use Input;
use Loopeer\QuickCms\Models\Permission;
use Validator;
use Session;
use Loopeer\QuickCms\Http\Controllers\IndexController;
use Cache;
use Redirect;
use Loopeer\QuickCms\Models\System;

class AdminMiddleware{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::admin()->check()) {
            return redirect('/admin/login');
        } else {
            $last_activity_time = $request->session()->get('LAST_ACTIVITY', Carbon::now());
            //判断登录是否失效
            if (time() - strtotime($last_activity_time) > config('quickcms.login_lifetime', 10) * 60) {
                Auth::admin()->logout();
                Session::forget('menu');
                Session::forget('permissions');
                Session::forget('business_id');
                return redirect('/admin/login');
            }
        }
        if ($_SERVER['REQUEST_URI'] == '/admin/logs') {
            if (!Auth::admin()->get()->can('admin.logs')) {
                return Redirect::to('/admin/index')->with('message', array('result'=>false, 'content'=>'您没有权限'));
            }
        }
        if(!$request->session()->has('menu')){
            $user = Auth::admin()->get();
            $this->getMenus($user);
        }
        //设置最后操作时间
        $request->session()->put('LAST_ACTIVITY', Carbon::now());
        return $next($request);
    }

    private function getMenus($user) {
        $menus = Permission::with('menus')->where('parent_id', 0)->orderBy('sort')->get();
        if(isset($user)) {
            $business_id = 0;
            if (config('quickCms.business_user_model_class')) {
                $reflectionClass = new \ReflectionClass(config('quickCms.business_user_model_class'));
                $business_user = $reflectionClass->newInstance();
                $business_user = $business_user::where('admin_id', Auth::admin()->get()->id)->first();
                $business_id = count($business_user) ? $business_user->business_id : 0;
            }
            Session::put('business_id', $business_id);
            foreach($menus as $key=>$menu){
                $items = Permission::where('parent_id', $menu->id)->orderBy('sort')->where('type', 0)->get();
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
}
