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
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Input;
use Validator;
use Loopeer\QuickCms\Models\User;

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
        $password = sha1($password.config('api.admin_pwd_salt'));
        $admin = User::where('email',$email)->where('password',$password)->first();
        if(is_null($admin)){
            $message = array('result' => false,'content' => '邮箱或密码错误');
            return redirect('/admin/login')->with('message',$message);
        }
        if($admin->status == 0){
            $message = array('result' => false,'content' => '此用户已被禁用');
            return redirect('/admin/login')->with('message',$message);
        }
        return $next($request);
    }
}