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
        if (substr($_SERVER['REQUEST_URI'], 0 , 5) == '/logs') {
            \Log::info(Auth::admin()->get()->can('admin.logs'));
            if (!Auth::admin()->get()->can('admin.logs')) {
                return Redirect::to('/admin/index')->with('message', array('result'=>false, 'content'=>'您没有权限'));
            }
        }
        $menus = Session::get('menu',null);
        if(is_null($menus)){
            $user = Auth::admin()->get();
            $index = new IndexController($request);
            $index->getMenus($user);
        }
        if (!Cache::has('websiteTitle')) {
            Cache::rememberForever('websiteTitle', function() {
                return System::find(1)['title'];
            });
        }
        return $next($request);
    }
}