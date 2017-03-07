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
namespace Loopeer\QuickCms\Models\Backend;

use Eloquent;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Support\Facades\Hash;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Loopeer\QuickCms\Services\Utils\QiniuUtil;

class User extends FastModel implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, EntrustUserTrait;

    protected $fillable = ['name', 'email', 'password', 'remember_token', 'status', 'last_login'];

    protected $buttons = ['detail' => false, 'delete' => false, 'actions' => [
            [
                'type' => 'confirm', 'name' => 'enabled_btn', 'text' => '启用',
                'url' => '/admin/users', 'data' => ['status' => 0], 'where' => ['status' => [1]]
            ],
            [
                'type' => 'confirm', 'name' => 'unabled_btn', 'text' => '禁用',
                'url' => '/admin/users', 'data' => ['status' => 1], 'where' => ['status' => [0]]
            ]
        ]
    ];
    protected $index = [
        ['column' => 'id'],
        ['column' => 'name'],
        ['column' => 'email'],
        ['column' => 'roles.display_name'],
        ['column' => 'avatar'],
        ['column' => 'last_login'],
        ['column' => 'status', 'type' => 'normal', 'param' => ['<span class="label label-success">正常</span>', '<span class="label label-default">禁用</span>']],
        ['column' => 'created_at'],
    ];

    protected $create = [
        ['column' => 'name', 'rules' => ['required' => true]],
        ['column' => 'email', 'rules' => ['required' => true]],
        ['column' => 'password', 'type' => 'password', 'rules' => ['required' => true]],
        ['column' => 'roles.id', 'type' => 'select', 'param' => 'role_group'],
    ];
    protected $module = '用户';

    protected $casts = ['avatar' => 'qiniu'];
    protected $with = ['roles'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
