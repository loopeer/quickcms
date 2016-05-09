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
use Closure;
use Illuminate\Contracts\Auth\Guard;
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
        return $next($request);
    }

    private function getMenus($user) {
        $menus = Permission::with('menus')->where('parent_id', 0)->orderBy('sort')->get();
        if(isset($user)) {
            foreach($menus as $key=>$menu){
                $items = Permission::where('parent_id', $menu->id)->where('type', 0)->get();
                \Log::info($items);
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
