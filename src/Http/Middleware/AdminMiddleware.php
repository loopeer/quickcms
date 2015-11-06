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
        $menus = Session::get('menu',null);
        if(is_null($menus)){
            $user = Auth::admin()->get();
            $index = new IndexController($request);
            $index->getMenus($user);
        }
        return $next($request);
    }
}