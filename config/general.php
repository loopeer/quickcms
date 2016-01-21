<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/12/15
 * Time: 下午6:20
 */
return [
    //版本管理
    'versions_create_able' => true,  //是否可创建
    'versions_middleware'=>array(
        'admin.versions'
    ),
    'versions_model_class' => 'Loopeer\QuickCms\Models\Version',
    'versions_model_name' => '版本',
    'versions_index_select_column' => array(
        'versions.id', 'versions.platform', 'versions.version_code', 'versions.version', 'versions.url', 'versions.message',
        'versions.description', 'versions.status','versions.published_at',
    ),
    'versions_index_column' => array(
        'id', 'platform', 'version_code', 'version', 'url', 'message', 'description', 'status','published_at',
    ),
    'versions_index_column_name' => array(
        'ID', '发布平台', '版本号', '版本名称', '下载地址', '消息提示', '版本描述', '版本状态', '发布时间', '选项',
    ),
    'versions_index_where' => array(
        array('column' => 'id', 'operator' => '>', 'value' => 0)
    ),
    'versions_edit_column' => array(
        'platform', 'version_code', 'version', 'url', 'description', 'published_at', 'status'
    ),
    'versions_edit_column_name' => array(
        '发布平台', '版本号', '版本名称', '下载地址', '版本描述', '发布时间','版本状态',
    ),
    'versions_index_column_rename' => array(
        // 字段名配置
        'status' => array(
            //正常状态显示替换
            'type'=>'normal',
            'param'=>array(
                //数据库值为键名，显示内容为键值
                0 => array(
                    'value' => '<span class="label label-default">未上线</span>',
                    'action_name' => 'online_btn'
                ),
                1 => array(
                    'value' => '<span class="label label-success">已上线</span>',
                    'action_name' => 'offline_btn'
                )
            ),
        ),
        // 字段名配置
        'version_code'=>array(
            //点击数字出现模态框
            'type' => 'dialog',
            'param'=>array(
                // a标签name名称
                'name' => 'test_btn',
                //模态框名称
                'target' => 'test_dialog',
                //模态框标题
                'dialog_title' => 'Modal',
                //模态框路由，结尾的'/'不能省略，url后会传递id值，路由需配置
                'url' => '/admin/test/detail/'
            )
        )
    ),
    // 下拉按钮配置
    'versions_table_action' => array(
        array(
            'default_show' => true,
            'type' => 'edit',
            'name' => 'edit_btn',
            'display_name' => '编辑',
            'has_divider' => true
        ),
        array(
            'default_show' => false,
            'type' => 'confirm',
            'name' => 'online_btn',
            'display_name' => '上线',
            'has_divider' => true,
            'method' => 'get',
            'url' => '/admin/versions/changeStatus',
            'confirm_msg' => '确定要上线吗?',
            'success_msg' => '操作成功',
            'failure_msg' => '操作失败'
        ),
        array(
            'default_show' => false,
            'type' => 'confirm',
            'name' => 'offline_btn',
            'display_name' => '下线',
            'has_divider' => true,
            'method' => 'get',
            'url' => '/admin/versions/changeStatus',
            'confirm_msg' => '确定要下线吗?',
            'success_msg' => '操作成功',
            'failure_msg' => '操作失败'
        ),
        array(
            'default_show' => true,
            'type' => 'confirm',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'method' => 'delete',
            'url' => '/admin/versions',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'versions_edit_column_detail' => array(
        'published_at'=>array(
            //type 暂时只支持 date/time/selector，selector 需要配置 key 值，例：'type'=>'selector:key'
            'type'=>'date',
            //验证暂时只支持 true 或 false 类型
            'validator'=>array('required'=>true),
            //message 为选填
            'message'=>array('required'=>'必需填写时间发布时间')
        ),
        'platform'=>array(
            'type'=>'selector:platform',
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true, 'number'=>true),
            'message'=>array('number'=>'必需为数字')
        )
    ),

    //意见反馈
    'feedbacks_create_able' => false,
    'feedbacks_middleware'=>array(
        'admin.feedbacks'
    ),
    'feedbacks_model_class' => 'Loopeer\QuickCms\Models\Feedback',
    'feedbacks_model_name' => '意见',
    'feedbacks_index_column' => array(
        'id', 'content', 'version', 'version_code', 'device_id', 'channel_id', 'contact',
    ),
    'feedbacks_index_column_name' => array(
        'ID', '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式', '选项',
    ),
    'feedbacks_edit_column' => array(
        'content', 'version_code', 'version', 'device_id', 'channel_id', 'contact'
    ),
    'feedbacks_edit_column_name' => array(
        '反馈内容', '版本名称', '版本号', '设备唯一ID', '渠道编号', '联系方式'
    ),
    'feedbacks_table_action' => array(
        array(
            'default_show' => true,
            'type' => 'confirm',
            'method' => 'delete',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败',
            'url' => '/admin/feedbacks',
        )
    ),

    //登录日志管理
    'actionLogs_create_able' => false,  //是否可创建
    'actionLogs_model_class' => 'Loopeer\QuickCms\Models\ActionLog',
    'actionLogs_model_name' => '操作日志',
    'actionLogs_index_multi' => true,
    'actionLogs_index_multi_join' => array(
        ['users', 'action_logs.user_id', '=', 'users.id'],
        ['users as users2', 'action_logs.user_id', '=', 'users2.id'],
    ),
    'actionLogs_index_multi_column' => array(
        'action_logs.id', 'users2.name', 'users.email', 'action_logs.client_ip', 'action_logs.created_at',
    ),
    'actionLogs_index_column' => array(
        'id', 'name', 'email', 'client_ip', 'created_at',
    ),
    'actionLogs_index_column_name' => array(
        'ID', '姓名', '邮箱', 'IP', '时间'
    ),

    // 权限管理
    'permissions_create_able' => true,  //是否可创建
    'permissions_middleware'=>array(
        'admin.permissions'
    ),
    'permissions_model_class' => 'Loopeer\QuickCms\Models\Permission',
    'permissions_model_name' => '权限',
    'permissions_index_select_column' => array(
        'permissions.id', 'permissions.name', 'permissions.display_name', 'permissions.route'
    ),
    'permissions_index_multi' => true,
    'permissions_index_multi_join' => array(
        ['permissions as parents', 'parents.id', '=', 'menus.parent_id'],
    ),
    'permissions_index_multi_column' => array(
        'menus.id as menu_id',
        'permissions.name as menu_name',
        'permissions.display_name as menu_display_name',
        'permissions.route as menu_route',
        'parents.display_name as parent_display_name',
        'permissions.sort as menu_sort',
        'permissions.icon as menu_icon',
        'permissions.description as menu_description'
    ),
    'permissions_index_column' => array(
        'menu_id', 'menu_name', 'menu_display_name', 'menu_name', 'parent_display_name', 'menu_sort', 'menu_icon', 'menu_description'
    ),
    'permissions_index_column_name' => array(
        '编号', '名称', '显示名称', '路由', '上级权限', '排序', '图标', '描述', '操作'
    ),
    'permissions_edit_column' => array(
        'name', 'display_name', 'parent_id', 'route', 'sort', 'icon', 'description'
    ),
    'permissions_edit_column_name' => array(
        '权限名称 如：admin.permissions.create或者permissions#',
        '权限显示名称',
        '父权限',
        '权限路由 如：/admin/permissions/create,如果为一级菜单可以为：#',
        '排序',
        '显示图标',
        '权限描述',
    ),
    // 下拉按钮配置
    'permissions_table_action' => array(
        array(
            'default_show' => true,
            'type' => 'edit',
            'name' => 'edit_btn',
            'display_name' => '编辑',
            'has_divider' => true
        ),
        array(
            'default_show' => true,
            'type' => 'confirm',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'method' => 'delete',
            'url' => '/admin/permissions',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'permissions_edit_column_detail' => array(
        'parent_id'=>array(
            //type 暂时只支持 date/time/selector，selector 需要配置 key 值，例：'type'=>'selector:key'
            'type'=>'selector:parent_permissions',
            //验证暂时只支持 true 或 false 类型
            'validator'=>array('required'=>true),
            //message 为选填
//            'message'=>array('required'=>'必需填写时间发布时间')
        ),
        'name'=>array(
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true),
        ),
        'display_name'=>array(
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true),
        ),
    ),

    //角色管理
    'roles_create_able' => true,  //是否可创建
    'roles_middleware'=>array(
        'admin.roles'
    ),
    'roles_model_class' => 'Loopeer\QuickCms\Models\Role',
    'roles_model_name' => '角色',
    'roles_index_select_column' => array(
        'roles.id','roles.name','roles.display_name','roles.description'
    ),
    'roles_index_column' => array(
        'id','name','display_name','description'
    ),
    'roles_index_column_name' => array(
        '编号', '名称', '显示名称', '描述','操作'
    ),
    'roles_edit_column' => array(
        'name', 'display_name', 'description'
    ),
    'roles_edit_column_name' => array(
        '名称', '显示名称', '描述'
    ),
    // 下拉按钮配置
    'roles_table_action' => array(
        array(
            'default_show' => true,
            'type' => 'dialog',
            'name' => 'edit_permission',
            'target' => 'edit_permission',
            'dialog_title' => '分配权限',
            'display_name' => '分配权限',
            'has_divider' => true,
            'url' => '/admin/roles/permissions/',
            'form' => array(
                'form_id' => 'smart-form-permissions',
                'submit_id' => 'confirmPermission',
                'success_msg' => '分配成功',
                'failure_msg' => '分配失败'
            ),
        ),
        array(
            'default_show' => true,
            'type' => 'edit',
            'name' => 'edit_btn',
            'display_name' => '编辑',
            'has_divider' => true
        ),
        array(
            'default_show' => true,
            'type' => 'confirm',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'method' => 'delete',
            'url' => '/admin/roles',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'roles_edit_column_detail' => array(
        'name'=>array(
            'validator'=>array('required'=>true),
        ),
        'display_name'=>array(
            'validator'=>array('required'=>true),
        ),
    ),

    //用户管理，仅列表页
    'users_create_able' => true,  //是否可创建
    // 必须配置 TODO
    'users_middleware'=>array(
        'admin.users'
    ),
    'users_model_class' => 'Loopeer\QuickCms\Models\User',
    'users_model_name' => '用户',
    'users_index_select_column' => array(
        'users.id', 'users.name', 'users.email', 'created_at', 'last_login'
    ),
    'users_index_multi' => true,
    'users_index_multi_join' => array(
        ['role_user', 'users.id', '=', 'role_user.user_id'],
        ['roles','roles.id','=','role_user.role_id'],
    ),
    'users_index_multi_column' => array(
        'users.id as user_id','users.name as user_name','users.email as email','users.created_at as user_created_at',
        'users.last_login as last_login','users.status as status','roles.display_name as role_name'
    ),
    'users_index_column' => array(
        'user_id','user_name','email','role_name','user_created_at','last_login','status'
    ),
    'users_index_column_name' => array(
        'ID', '姓名', '邮箱', '所属角色', '创建时间', '最后登录时间', '状态', '操作'
    ),
    'users_index_column_rename' => array(
        // 字段名配置
        'status' => array(
            //正常状态显示替换
            'type'=>'normal',
            'param'=>array(
                //数据库值为键名，显示内容为键值
                0 => array(
                    'value' => '<span class="label label-default">已禁用</span>',
                    'action_name' => 'enabled_btn'
                ),
                1 => array(
                    'value' => '<span class="label label-success">已启用</span>',
                    'action_name' => 'disabled_btn'
                )
            ),
        ),
    ),
    // 下拉按钮配置
    'users_table_action' => array(
        array(
            'default_show' => true,
            'type' => 'dialog',
            'name' => 'set_role',
            'target' => 'set_role',
            'dialog_title' => '分配角色',
            'display_name' => '分配角色',
            'has_divider' => true,
            'url' => '/admin/users/role/',
            'form' => array(
                'form_id' => 'smart-form-register',
                'submit_id' => 'submitRole',
                'success_msg' => '分配成功',
                'failure_msg' => '分配失败'
            ),
        ),
        array(
            'default_show' => true,
            // type: edit/confirm/dialog，其他都可自定义
            'type' => 'edit',
            'name' => 'edit_btn',
            'display_name' => '编辑',
            'has_divider' => true
        ),
        array(
            'default_show' => false,
            'type' => 'confirm',
            'name' => 'enabled_btn',
            'display_name' => '启用',
            'has_divider' => true,
            'method' => 'get',
            'url' => '/admin/users/changeStatus',
            'confirm_msg' => '确定要启用吗?',
            'success_msg' => '操作成功',
            'failure_msg' => '操作失败'
        ),
        array(
            'default_show' => false,
            'type' => 'confirm',
            'name' => 'disabled_btn',
            'display_name' => '禁用',
            'has_divider' => true,
            'method' => 'get',
            'url' => '/admin/users/changeStatus',
            'confirm_msg' => '确定要禁用吗?',
            'success_msg' => '操作成功',
            'failure_msg' => '操作失败'
        ),
        array(
            'default_show' => true,
            'type' => 'confirm',
            'name' => 'delete_btn',
            'display_name' => '删除',
            'has_divider' => false,
            'method' => 'delete',
            'url' => '/admin/users',
            'confirm_msg' => '确定要删除吗?',
            'success_msg' => '删除成功',
            'failure_msg' => '删除失败'
        )
    ),
    'users_edit_column_detail' => array(
        'published_at'=>array(
            //type 暂时只支持 date/time/selector，selector 需要配置 key 值，例：'type'=>'selector:key'
            'type'=>'date',
            //验证暂时只支持 true 或 false 类型
            'validator'=>array('required'=>true),
            //message 为选填
            'message'=>array('required'=>'必需填写时间发布时间')
        ),
        'platform'=>array(
            'type'=>'selector:platform',
            //验证暂时只支持 true 或 false
            'validator'=>array('required'=>true, 'number'=>true),
            'message'=>array('number'=>'必需为数字')
        ),
        'image' => array(
            //type image 类型 image:[1,3]，或者 [1]
            'type' => 'image',
            'min_count' => 1,
            'max_count' => 3,
            'min_error_msg' => '至少上传%s张图片',
            'max_error_msg' => '最多只允许上传%s张图片',
            'editable' => true
        ),
    ),

];