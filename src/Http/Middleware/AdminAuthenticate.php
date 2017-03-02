<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-10-22
 * Time: 下午2:32
 */
namespace Loopeer\QuickCms\Http\Middleware;

use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Input;
use Validator;
use Loopeer\QuickCms\Models\Backend\User;

class AdminAuthenticate{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
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
        $email = Input::get('email');
        $password = Input::get('password');
        if (Auth::admin()->attempt(['email' => $email, 'password' => $password], true)) {
            User::where('email', $email)->update(['last_login' => Carbon::now()]);
            //设置最后操作时间
            $request->session()->put('LAST_ACTIVITY', Carbon::now());
            // 认证通过...
            return redirect('/admin/index');
        } else {
            $message = array('result' => false,'content' => '邮箱或密码错误');
            return redirect('/admin/login')->with('message', $message);
        }
    }
}