<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: msy
 * Date: 15-10-22
 * Time: 下午10:43
 */
namespace Loopeer\QuickCms\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\App;
use Redirect;
use Input;
use Validator;

class PermissionMiddleware {

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next,$permissions)
    {
        $admin = Auth::admin()->get();
        if(!$admin->can(explode(',', $permissions))){
            App::abort('404');
        }
        return $next($request);
    }

}
