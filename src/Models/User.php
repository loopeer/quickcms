<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/11
 * Time: 上午11:39
 */
namespace Loopeer\QuickCms\Models;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class User extends Eloquent  implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;

    protected $fillable = ['name', 'email','password','remember_token','status','last_login'];

    public function getAvatarAttribute(){
        if(isset($this->attributes['avatar']) && $this->attributes['avatar'] != '') {
            $image = QiniuUtil::buildQiqiuImageUrl($this->attributes['avatar']);
            return $image;
        } else {
            return $this->attributes['avatar'];
        }
    }
}
