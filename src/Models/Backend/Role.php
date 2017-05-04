<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/11
 * Time: 上午11:38
 */
namespace Loopeer\QuickCms\Models\Backend;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;
use Zizaco\Entrust\Traits\EntrustRoleTrait;

class Role extends FastModel implements EntrustRoleInterface
{
    use EntrustRoleTrait;

    protected $buttons = ['detail' => false, 'delete' => false,
        'actions' => [
            [
                'type' => 'dialog',
                'name' => 'edit_permission',
                'target' => 'edit_permission',
                'method' => 'get',
                'permission' => 'admin.roles.permissions',
                'dialog_title' => '分配权限',
                'text' => '分配权限',
                'url' => '/admin/roles/permissions/',
                'form' => [
                    'form_id' => 'smart-form-permissions',
                    'submit_id' => 'confirmPermission',
                    'success_msg' => '分配权限成功，重新登陆后即可更新左侧菜单栏',
                    'failure_msg' => '分配失败'
                ],
            ],
        ]
    ];
    protected $index = [
        ['column' => 'id', 'order' => 'desc'],
        ['column' => 'name', 'query' => 'like'],
        ['column' => 'display_name', 'query' => 'like'],
        ['column' => 'description', 'query' => 'like'],
        ['column' => 'created_at', 'order' => 'desc'],
    ];
    protected $create = [
        ['column' => 'name', 'rules' => ['required' => true]],
        ['column' => 'display_name', 'rules' => ['required' => true]],
        ['column' => 'description'],
    ];
    protected $module = '角色';

    public function users()
    {
        return $this->belongsToMany(config('auth.multi-auth.admin.model'),config('entrust.role_user_table'));
    }
}
