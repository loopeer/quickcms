<?php
/**
 * Copyright (C) Loopeer, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential.
 *
 * User: DengYongBin
 * Date: 15/9/10
 * Time: 上午10:12
 */
namespace Loopeer\QuickCms\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Loopeer\QuickCms\Models\Backend\RoleUser;
use Loopeer\QuickCms\Models\Backend\User;

class UserController extends FastController
{
    public function storeUser()
    {
        $data = Input::all();
        if ($data['id']) {
            $exist_user = User::whereNotIn('id', [$data['id']])->where('email', $data['email'])->first();
            if (isset($exist_user)) {
                $message = ['result' => false, 'content' => '编辑用户失败，此邮箱已存在'];
            } else {
                User::find($data['id'])->update($data);
                $roleUser = RoleUser::firstOrNew(['user_id' => $data['id']]);
                $roleUser->role_id = $data['roles_id'];
                $roleUser->save();
                $message = ['result' => true, 'content' => '编辑用户成功'];
            }
        } else {
            $user = User::firstOrNew(['email' => $data['email']]);
            if (!$user->exists) {
                $user->name = $data['name'];
                $user->password =  $data['password'];
                $user->save();
                RoleUser::create(array(
                    'user_id' => $user->id,
                    'role_id' => $data['roles_id']
                ));
                $message = ['result' => true, 'content' => '创建用户成功'];
            } else {
                $message = ['result' => false, 'content' => '创建用户失败，此邮箱已存在'];
            }
        }
        return redirect()->to('admin/users')->with('message', $message);
    }

    public function getProfile()
    {
        $user = Auth::admin()->get();
        $image = array(
            'column' => 'image',
            'min' => 1,
            'max' => 1,
            'min_error_msg' => '至少上传%s张图片',
            'max_error_msg' => '最多只允许上传%s张图片',
        );
        return view('backend::users.update', compact('user', 'image'));
    }

    public function saveProfile()
    {
        $data = Input::all();
        $data['avatar'] = $data['image'];
        unset($data['image']);
        unset($data['passwordConfirm']);
        User::find(Auth::admin()->get()->id)->update($data);
        $message = array('result' => true, 'content' => '保存成功');
        return redirect('admin/users/profile')->with('message', $message);
    }

    public function register(Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');
        $password = $request->input('password');
        $user = User::firstOrNew(['email' => $email]);
        if (!$user->exists) {
            $user->name = $name;
            $user->password = $password;
            $user->status = User::STATUS_FORBIDDEN;
            $user->save();
            $result = ['result' => true, 'content' => '申请成功，请等待后台管理员审核'];
        } else {
            $result = ['result' => false, 'content' => '申请失败，此邮箱已存在'];
        }
        return $result;
    }
}
